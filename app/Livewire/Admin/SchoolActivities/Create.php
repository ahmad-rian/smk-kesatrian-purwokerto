<?php

namespace App\Livewire\Admin\SchoolActivities;

use App\Models\SchoolActivity;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Auth;

/**
 * Livewire Component untuk membuat Kegiatan Sekolah baru
 * 
 * Fitur:
 * - Form input lengkap dengan validasi real-time
 * - Upload gambar utama dengan preview
 * - Auto-generate slug berdasarkan nama kegiatan
 * - Pilihan kategori kegiatan yang tersedia
 * - Validasi tanggal mulai dan selesai
 * - Toast notification untuk feedback
 * - Redirect ke index setelah berhasil
 */
#[Layout('livewire.admin.layout')]
class Create extends Component
{
    use WithFileUploads, Toast;

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
     * Validation rules
     */
    protected function rules(): array
    {
        return SchoolActivity::validationRules();
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return SchoolActivity::validationMessages();
    }

    /**
     * Real-time validation dan auto-generate slug
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
        
        // Auto-generate slug ketika nama kegiatan diubah (jika slug masih kosong)
        if ($propertyName === 'nama_kegiatan' && empty($this->slug) && !empty(trim($this->nama_kegiatan))) {
            $this->generateSlugAuto();
        }

        // Validasi tanggal selesai tidak boleh kurang dari tanggal mulai
        if ($propertyName === 'tanggal_selesai' && !empty($this->tanggal_mulai) && !empty($this->tanggal_selesai)) {
            if ($this->tanggal_selesai < $this->tanggal_mulai) {
                $this->addError('tanggal_selesai', 'Tanggal selesai tidak boleh kurang dari tanggal mulai.');
            }
        }
    }

    /**
     * Mount component - set default values
     */
    public function mount(): void
    {
        // Set tanggal mulai default ke hari ini
        $this->tanggal_mulai = now()->format('Y-m-d');
        $this->tanggal_selesai = now()->format('Y-m-d');
    }

    /**
     * Method untuk menyimpan data
     */
    public function save(): void
    {
        // Validasi semua input
        $validatedData = $this->validate();

        try {
            // Tambahkan user yang membuat
            $validatedData['dibuat_oleh'] = Auth::id();

            // Konversi tanggal jika ada
            if (!empty($this->tanggal_mulai)) {
                $validatedData['tanggal_mulai'] = $this->tanggal_mulai;
            }
            if (!empty($this->tanggal_selesai)) {
                $validatedData['tanggal_selesai'] = $this->tanggal_selesai;
            }

            // Buat kegiatan sekolah baru
            SchoolActivity::create($validatedData);

            $this->success(
                title: 'Berhasil!',
                description: "Kegiatan '{$this->nama_kegiatan}' berhasil dibuat.",
                position: 'toast-top toast-end'
            );

            // Redirect ke index
            $this->redirect(route('admin.school-activities.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
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
     * Method untuk generate slug otomatis tanpa notifikasi (dipanggil otomatis)
     */
    public function generateSlugAuto(): void
    {
        if (empty(trim($this->nama_kegiatan))) {
            return;
        }

        try {
            // Generate slug dari nama kegiatan
            $slug = \Illuminate\Support\Str::slug($this->nama_kegiatan);
            
            // Cek apakah slug sudah ada, jika ya tambahkan angka
            $originalSlug = $slug;
            $counter = 1;

            while (SchoolActivity::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $this->slug = $slug;
            
        } catch (\Exception $e) {
            // Silent fail untuk auto-generate
        }
    }

    /**
     * Method untuk generate slug otomatis berdasarkan nama (dengan notifikasi)
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
            
            // Cek apakah slug sudah ada, jika ya tambahkan angka
            $originalSlug = $slug;
            $counter = 1;

            while (SchoolActivity::where('slug', $slug)->exists()) {
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
     * Method untuk preview gambar
     */
    public function getImagePreviewProperty(): ?string
    {
        return $this->gambar_utama ? $this->gambar_utama->temporaryUrl() : null;
    }

    /**
     * Method untuk menghapus gambar yang diupload
     */
    public function removeImage(): void
    {
        $this->gambar_utama = null;
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
        return view('livewire.admin.school-activities.create', [
            'kategoriOptions' => $this->kategoriOptions,
        ]);
    }
}