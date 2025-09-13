<?php

namespace App\Livewire\Admin\StudyPrograms;

use App\Models\StudyProgram;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Mary\Traits\Toast;

/**
 * Livewire Component untuk membuat Program Studi baru
 * 
 * Fitur:
 * - Form input lengkap dengan validasi real-time
 * - Upload gambar dengan preview
 * - Dynamic input untuk kompetensi dan prospek karir
 * - Auto-generate urutan berdasarkan data terakhir
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
     * Validation rules
     */
    protected function rules(): array
    {
        return StudyProgram::validationRules();
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return StudyProgram::validationMessages();
    }

    /**
     * Real-time validation dan auto-generate kode
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);

        // Auto-generate kode ketika nama diubah (jika kode masih kosong)
        if ($propertyName === 'nama' && empty($this->kode) && !empty(trim($this->nama))) {
            $this->generateKodeAuto();
        }
    }

    /**
     * Mount component - set default values
     */
    public function mount(): void
    {
        // Set urutan default berdasarkan data terakhir
        $lastProgram = StudyProgram::orderBy('urutan', 'desc')->first();
        $this->urutan = $lastProgram ? $lastProgram->urutan + 1 : 1;
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
     * Method untuk menyimpan data
     */
    public function save(): void
    {
        // Validasi semua input
        $validatedData = $this->validate();

        try {
            // Filter array kosong
            $validatedData['kompetensi'] = array_filter($this->kompetensi, fn($item) => !empty(trim(is_string($item) ? $item : '')));
            $validatedData['prospek_karir'] = array_filter($this->prospek_karir, fn($item) => !empty(trim(is_string($item) ? $item : '')));

            // Buat program studi baru
            StudyProgram::create($validatedData);

            $this->success(
                title: 'Berhasil!',
                description: "Program studi '{$this->nama}' berhasil dibuat.",
                position: 'toast-top toast-end'
            );

            // Redirect ke index
            $this->redirect(route('admin.study-programs.index'), navigate: true);
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
        $this->redirect(route('admin.study-programs.index'), navigate: true);
    }

    /**
     * Method untuk generate kode otomatis tanpa notifikasi (dipanggil otomatis)
     */
    public function generateKodeAuto(): void
    {
        if (empty(trim($this->nama))) {
            return;
        }

        try {
            // Bersihkan nama dari karakter khusus
            $cleanNama = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->nama);

            // Ambil huruf pertama dari setiap kata
            $words = explode(' ', strtoupper(trim($cleanNama)));
            $kode = '';

            foreach ($words as $word) {
                $word = trim($word);
                if (!empty($word)) {
                    $kode .= substr($word, 0, 1);
                }
            }

            // Pastikan minimal 2 karakter, maksimal 5 karakter
            if (strlen($kode) < 2) {
                $kode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->nama), 0, 3));
            } elseif (strlen($kode) > 5) {
                $kode = substr($kode, 0, 5);
            }

            // Cek apakah kode sudah ada, jika ya tambahkan angka
            $originalKode = $kode;
            $counter = 1;

            while (StudyProgram::where('kode', $kode)->exists()) {
                $kode = $originalKode . $counter;
                $counter++;

                // Batasi maksimal 10 karakter
                if (strlen($kode) > 10) {
                    $kode = substr($originalKode, 0, 8) . $counter;
                }
            }

            $this->kode = $kode;
        } catch (\Exception $e) {
            // Silent fail untuk auto-generate
        }
    }

    /**
     * Method untuk generate kode otomatis berdasarkan nama (dengan notifikasi)
     */
    public function generateKode(): void
    {
        if (empty(trim($this->nama))) {
            $this->error(
                title: 'Peringatan!',
                description: 'Nama program studi harus diisi terlebih dahulu.',
                position: 'toast-top toast-end'
            );
            return;
        }

        try {
            // Bersihkan nama dari karakter khusus
            $cleanNama = preg_replace('/[^a-zA-Z0-9\s]/', '', $this->nama);

            // Ambil huruf pertama dari setiap kata
            $words = explode(' ', strtoupper(trim($cleanNama)));
            $kode = '';

            foreach ($words as $word) {
                $word = trim($word);
                if (!empty($word)) {
                    $kode .= substr($word, 0, 1);
                }
            }

            // Pastikan minimal 2 karakter, maksimal 5 karakter
            if (strlen($kode) < 2) {
                $kode = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->nama), 0, 3));
            } elseif (strlen($kode) > 5) {
                $kode = substr($kode, 0, 5);
            }

            // Cek apakah kode sudah ada, jika ya tambahkan angka
            $originalKode = $kode;
            $counter = 1;

            while (StudyProgram::where('kode', $kode)->exists()) {
                $kode = $originalKode . $counter;
                $counter++;

                // Batasi maksimal 10 karakter
                if (strlen($kode) > 10) {
                    $kode = substr($originalKode, 0, 8) . $counter;
                }
            }

            $this->kode = $kode;

            $this->success(
                title: 'Berhasil!',
                description: "Kode '{$this->kode}' berhasil di-generate.",
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat generate kode. Silakan coba lagi.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk preview gambar
     */
    public function getImagePreviewProperty(): ?string
    {
        return $this->gambar ? $this->gambar->temporaryUrl() : null;
    }

    /**
     * Method untuk menghapus gambar yang diupload
     */
    public function removeImage(): void
    {
        $this->gambar = null;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.study-programs.create');
    }
}
