<?php

namespace App\Livewire\Admin\SchoolActivities;

use App\Models\SchoolActivity;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ImageConversionService;

/**
 * Livewire Component untuk mengedit Kegiatan Sekolah
 * 
 * Fitur:
 * - Form edit dengan data yang sudah ada
 * - Upload gambar baru dengan preview gambar lama
 * - Auto-generate slug berdasarkan nama kegiatan
 * - Pilihan kategori kegiatan yang tersedia
 * - Validasi tanggal mulai dan selesai
 * - Validasi real-time dengan unique validation
 * - Toast notification untuk feedback
 * - Redirect ke index setelah berhasil
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use WithFileUploads, Toast;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'confirm-delete' => 'confirmDelete'
    ];

    /**
     * Model instance
     */
    public SchoolActivity $schoolActivity;

    /**
     * Form properties
     */
    public string $nama_kegiatan = '';
    public string $slug = '';
    public string $deskripsi = '';
    public $gambar_utama = null;
    public string $kategori = '';
    public string $tanggal_mulai = '';
    public string $tanggal_selesai = '';
    public string $lokasi = '';
    public string $penanggungjawab = '';
    public bool $aktif = true;
    public bool $unggulan = false;

    /**
     * Property untuk menyimpan gambar lama
     */
    public ?string $currentImage = null;

    /**
     * Property untuk modal konfirmasi hapus
     */
    public bool $showDeleteModal = false;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return SchoolActivity::validationRules($this->schoolActivity->id);
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return SchoolActivity::validationMessages();
    }

    /**
     * Real-time validation
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);

        // Validasi tanggal selesai tidak boleh kurang dari tanggal mulai
        if ($propertyName === 'tanggal_selesai' && !empty($this->tanggal_mulai) && !empty($this->tanggal_selesai)) {
            if ($this->tanggal_selesai < $this->tanggal_mulai) {
                $this->addError('tanggal_selesai', 'Tanggal selesai tidak boleh kurang dari tanggal mulai.');
            }
        }
    }

    /**
     * Mount component - load existing data
     */
    public function mount(SchoolActivity $schoolActivity): void
    {
        $this->schoolActivity = $schoolActivity;

        // Load existing data
        $this->nama_kegiatan = $schoolActivity->nama_kegiatan;
        $this->slug = $schoolActivity->slug ?? '';
        $this->deskripsi = $schoolActivity->deskripsi ?? '';
        $this->currentImage = $schoolActivity->gambar_utama;
        $this->kategori = $schoolActivity->kategori ?? '';
        $this->tanggal_mulai = $schoolActivity->tanggal_mulai ? $schoolActivity->tanggal_mulai->format('Y-m-d') : '';
        $this->tanggal_selesai = $schoolActivity->tanggal_selesai ? $schoolActivity->tanggal_selesai->format('Y-m-d') : '';
        $this->lokasi = $schoolActivity->lokasi ?? '';
        $this->penanggungjawab = $schoolActivity->penanggungjawab ?? '';
        $this->aktif = $schoolActivity->aktif;
        $this->unggulan = $schoolActivity->unggulan;
    }

    /**
     * Method untuk update data
     */
    public function update(): void
    {
        // Validasi semua input
        $validatedData = $this->validate();

        try {
            // Konversi tanggal jika ada
            if (!empty($this->tanggal_mulai)) {
                $validatedData['tanggal_mulai'] = $this->tanggal_mulai;
            } else {
                $validatedData['tanggal_mulai'] = null;
            }
            
            if (!empty($this->tanggal_selesai)) {
                $validatedData['tanggal_selesai'] = $this->tanggal_selesai;
            } else {
                $validatedData['tanggal_selesai'] = null;
            }

            // Jika tidak ada gambar baru, hapus dari validated data
            if (!$this->gambar_utama) {
                unset($validatedData['gambar_utama']);
            }

            // Update kegiatan sekolah
            $this->schoolActivity->update($validatedData);

            $this->success(
                title: 'Berhasil!',
                description: "Kegiatan '{$this->nama_kegiatan}' berhasil diperbarui.",
                position: 'toast-top toast-end'
            );

            // Redirect ke index
            $this->redirect(route('admin.school-activities.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat memperbarui data. Silakan coba lagi.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk kembali ke index
     */
    public function cancel(): void
    {
        $this->redirect(route('admin.school-activities.index'), navigate: true);
    }

    /**
     * Method untuk menampilkan modal konfirmasi hapus
     */
    public function confirmDelete(): void
    {
        $this->showDeleteModal = true;
    }

    /**
     * Method untuk menghapus kegiatan sekolah
     */
    public function delete(): void
    {
        try {
            $nama = $this->schoolActivity->nama_kegiatan;
            
            // Hapus file gambar dari storage jika ada
            if ($this->schoolActivity->gambar_utama) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($this->schoolActivity->gambar_utama);
            }

            // Hapus record dari database
            $this->schoolActivity->delete();

            // Tutup modal
            $this->showDeleteModal = false;

            $this->success(
                title: 'Berhasil!',
                description: "Kegiatan '{$nama}' berhasil dihapus.",
                position: 'toast-top toast-end'
            );

            // Redirect ke index
            $this->redirect(route('admin.school-activities.index'), navigate: true);
        } catch (\Exception $e) {
            $this->showDeleteModal = false;
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menghapus kegiatan.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk generate slug otomatis berdasarkan nama kegiatan
     */
    public function generateSlug(): void
    {
        if (empty(trim($this->nama_kegiatan))) {
            $this->error(
                title: 'Peringatan!',
                description: 'Nama kegiatan harus diisi terlebih dahulu.',
                position: 'toast-top toast-end'
            );
            return;
        }

        try {
            // Generate slug dari nama kegiatan
            $slug = \Illuminate\Support\Str::slug($this->nama_kegiatan);
            
            // Cek apakah slug sudah ada (kecuali untuk record saat ini)
            $originalSlug = $slug;
            $counter = 1;

            while (SchoolActivity::where('slug', $slug)
                ->where('id', '!=', $this->schoolActivity->id)
                ->exists()
            ) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $this->slug = $slug;
            
            $this->success(
                title: 'Berhasil!',
                description: "Slug '{$this->slug}' berhasil di-generate.",
                position: 'toast-top toast-end'
            );
            
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat generate slug. Silakan coba lagi.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk preview gambar baru
     */
    public function getImagePreviewProperty(): ?string
    {
        if (!$this->gambar_utama) {
            return null;
        }

        try {
            // Pastikan file adalah instance UploadedFile yang valid
            if ($this->gambar_utama instanceof \Illuminate\Http\UploadedFile) {
                return $this->gambar_utama->temporaryUrl();
            }
        } catch (\Exception $e) {
            // Jika temporaryUrl gagal, return null untuk fallback ke placeholder
            Log::warning('Failed to generate temporary URL for image preview: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Method untuk URL gambar saat ini
     */
    public function getCurrentImageUrlProperty(): ?string
    {
        return $this->currentImage ? Storage::url($this->currentImage) : null;
    }

    /**
     * Method untuk menghapus gambar yang diupload
     */
    public function removeImage(): void
    {
        $this->gambar_utama = null;
    }

    /**
     * Method untuk menghapus gambar saat ini
     */
    public function removeCurrentImage(): void
    {
        if ($this->currentImage) {
            try {
                // Hapus file gambar dari storage
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($this->currentImage);
                
                // Update database untuk menghapus referensi gambar
                $this->schoolActivity->update(['gambar_utama' => null]);
                $this->currentImage = null;

                $this->success(
                    title: 'Berhasil!',
                    description: 'Gambar berhasil dihapus.',
                    position: 'toast-top toast-end'
                );
            } catch (\Exception $e) {
                $this->error(
                    title: 'Gagal!',
                    description: 'Terjadi kesalahan saat menghapus gambar.',
                    position: 'toast-top toast-end'
                );
            }
        }
    }

    /**
     * Method untuk mendapatkan opsi kategori
     */
    public function getKategoriOptionsProperty(): array
    {
        return SchoolActivity::getKategoriOptions();
    }

    /**
     * Method untuk set tanggal selesai otomatis sama dengan tanggal mulai
     */
    public function updatedTanggalMulai(): void
    {
        // Jika tanggal selesai masih kosong atau kurang dari tanggal mulai, set sama dengan tanggal mulai
        if (empty($this->tanggal_selesai) || $this->tanggal_selesai < $this->tanggal_mulai) {
            $this->tanggal_selesai = $this->tanggal_mulai;
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.school-activities.edit', [
            'kategoriOptions' => $this->kategoriOptions,
        ]);
    }
}