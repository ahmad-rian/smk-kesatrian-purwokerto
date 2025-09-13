<?php

namespace App\Livewire\Admin\StudyPrograms;

use App\Models\StudyProgram;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Mary\Traits\Toast;


/**
 * Livewire Component untuk mengedit Program Studi
 * 
 * Fitur:
 * - Form edit dengan data yang sudah ada
 * - Upload gambar baru dengan preview gambar lama
 * - Dynamic input untuk kompetensi dan prospek karir
 * - Validasi real-time dengan unique validation
 * - Toast notification untuk feedback
 * - Redirect ke index setelah berhasil
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use WithFileUploads, Toast;

    /**
     * Model instance
     */
    public StudyProgram $studyProgram;

    /**
     * Form properties
     */
    public string $kode = '';
    public string $nama = '';
    public string $deskripsi = '';
    public $gambar = null;
    public string $warna = '#3b82f6';
    public array $kompetensi = [''];
    public array $prospek_karir = [''];
    public string $ketua_program = '';
    public bool $aktif = true;
    public int $urutan = 1;

    /**
     * Property untuk menyimpan gambar lama
     */
    public ?string $currentImage = null;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return StudyProgram::validationRules($this->studyProgram->id);
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return StudyProgram::validationMessages();
    }

    /**
     * Real-time validation
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Mount component - load existing data
     */
    public function mount(StudyProgram $studyProgram): void
    {
        $this->studyProgram = $studyProgram;

        // Load existing data
        $this->kode = $studyProgram->kode;
        $this->nama = $studyProgram->nama;
        $this->deskripsi = $studyProgram->deskripsi ?? '';
        $this->currentImage = $studyProgram->gambar;
        $this->warna = $studyProgram->warna;
        $this->kompetensi = $studyProgram->kompetensi ?: [''];
        $this->prospek_karir = $studyProgram->prospek_karir ?: [''];
        $this->ketua_program = $studyProgram->ketua_program ?? '';
        $this->aktif = $studyProgram->aktif;
        $this->urutan = $studyProgram->urutan;
    }

    /**
     * Method untuk menambah field kompetensi
     */
    public function addKompetensi(): void
    {
        $this->kompetensi[] = '';
    }

    /**
     * Method untuk menghapus field kompetensi
     */
    public function removeKompetensi(int $index): void
    {
        if (count($this->kompetensi) > 1) {
            unset($this->kompetensi[$index]);
            $this->kompetensi = array_values($this->kompetensi);
        }
    }

    /**
     * Method untuk menambah field prospek karir
     */
    public function addProspekKarir(): void
    {
        $this->prospek_karir[] = '';
    }

    /**
     * Method untuk menghapus field prospek karir
     */
    public function removeProspekKarir(int $index): void
    {
        if (count($this->prospek_karir) > 1) {
            unset($this->prospek_karir[$index]);
            $this->prospek_karir = array_values($this->prospek_karir);
        }
    }

    /**
     * Method untuk update data
     */
    public function update(): void
    {
        // Validasi semua input
        $validatedData = $this->validate();

        try {
            // Filter array kosong - pastikan $item adalah string
            $validatedData['kompetensi'] = array_filter($this->kompetensi, fn($item) => !empty(trim(is_string($item) ? $item : '')));
            $validatedData['prospek_karir'] = array_filter($this->prospek_karir, fn($item) => !empty(trim(is_string($item) ? $item : '')));

            // Jika tidak ada gambar baru, hapus dari validated data
            if (!$this->gambar) {
                unset($validatedData['gambar']);
            }

            // Update program studi
            $this->studyProgram->update($validatedData);

            $this->success(
                title: 'Berhasil!',
                description: "Program studi '{$this->nama}' berhasil diperbarui.",
                position: 'toast-top toast-end'
            );

            // Redirect ke index
            $this->redirect(route('admin.study-programs.index'), navigate: true);
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
        $this->redirect(route('admin.study-programs.index'), navigate: true);
    }

    /**
     * Method untuk generate kode otomatis berdasarkan nama
     */
    public function generateKode(): void
    {
        if (empty($this->nama)) {
            return;
        }

        // Ambil huruf pertama dari setiap kata
        $words = explode(' ', strtoupper($this->nama));
        $kode = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $kode .= substr($word, 0, 1);
            }
        }

        // Pastikan minimal 2 karakter
        if (strlen($kode) < 2) {
            $kode = strtoupper(substr($this->nama, 0, 3));
        }

        // Cek apakah kode sudah ada (kecuali untuk record saat ini)
        $originalKode = $kode;
        $counter = 1;

        while (StudyProgram::where('kode', $kode)
            ->where('id', '!=', $this->studyProgram->id)
            ->exists()
        ) {
            $kode = $originalKode . $counter;
            $counter++;
        }

        $this->kode = $kode;
    }

    /**
     * Method untuk preview gambar baru
     */
    public function getImagePreviewProperty(): ?string
    {
        return $this->gambar ? $this->gambar->temporaryUrl() : null;
    }

    /**
     * Method untuk URL gambar saat ini
     */
    public function getCurrentImageUrlProperty(): ?string
    {
        return $this->currentImage ? asset('storage/' . $this->currentImage) : null;
    }

    /**
     * Method untuk menghapus gambar yang diupload
     */
    public function removeImage(): void
    {
        $this->gambar = null;
    }

    /**
     * Method untuk menghapus gambar saat ini
     */
    public function removeCurrentImage(): void
    {
        if ($this->currentImage) {
            // Set gambar kosong untuk dihapus saat update
            $this->studyProgram->update(['gambar' => null]);
            $this->currentImage = null;

            $this->success(
                title: 'Berhasil!',
                description: 'Gambar berhasil dihapus.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.study-programs.edit');
    }
}
