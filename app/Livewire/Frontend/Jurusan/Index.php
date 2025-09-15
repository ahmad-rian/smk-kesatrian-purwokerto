<?php

namespace App\Livewire\Frontend\Jurusan;

use App\Models\StudyProgram;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

/**
 * Komponen Livewire untuk halaman Program Studi/Jurusan
 * 
 * Menampilkan daftar program studi dengan desain modern:
 * - Card layout dengan gradient background
 * - Animasi hover dan interaksi
 * - Filter berdasarkan kategori
 * - Detail kompetensi dan prospek karir
 * - Responsive design untuk semua device
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Title('Program Studi - SMK Kesatrian')]
#[Layout('components.layouts.frontend')]
class Index extends Component
{
    /**
     * Data program studi yang akan ditampilkan
     */
    public $studyPrograms;

    /**
     * Program studi yang dipilih untuk detail
     */
    public $selectedProgram = null;

    /**
     * Status modal detail
     */
    public $showModal = false;

    /**
     * Filter kategori yang aktif
     */
    public $activeFilter = 'all';

    /**
     * Mount komponen dan ambil data program studi
     */
    public function mount()
    {
        $this->loadStudyPrograms();
    }

    /**
     * Load data program studi dari database
     */
    public function loadStudyPrograms()
    {
        $this->studyPrograms = StudyProgram::where('aktif', true)
            ->orderBy('urutan')
            ->get()
            ->map(function ($program) {
                return [
                    'id' => $program->id,
                    'kode' => $program->kode,
                    'nama' => $program->nama,
                    'deskripsi' => $program->deskripsi,
                    'warna' => $this->getProfessionalColor($program->kode),
                    'kompetensi' => $program->kompetensi ?? [],
                    'prospek_karir' => $program->prospek_karir ?? [],
                    'ketua_program' => $program->ketua_program,
                    'gambar' => $program->gambar_url,
                    'kategori' => $this->getKategori($program->kode)
                ];
            });
    }

    /**
     * Mendapatkan warna profesional berdasarkan kode program studi
     */
    private function getProfessionalColor($kode)
    {
        $colorMap = [
            'TKJ' => '#1e40af',  // Blue 700 - Teknologi Komputer
            'RPL' => '#059669',  // Emerald 600 - Rekayasa Perangkat Lunak
            'MM' => '#dc2626',   // Red 600 - Multimedia
            'OTKP' => '#7c3aed', // Violet 600 - Otomatisasi Tata Kelola Perkantoran
            'AKL' => '#ea580c',  // Orange 600 - Akuntansi Keuangan Lembaga
            'BDP' => '#0891b2'   // Cyan 600 - Bisnis Daring Pemasaran
        ];

        return $colorMap[$kode] ?? '#6b7280'; // Gray 500 sebagai default
    }

    /**
     * Mendapatkan kategori berdasarkan kode program studi
     */
    private function getKategori($kode)
    {
        $kategoriMap = [
            'TKJ' => 'Teknologi',
            'RPL' => 'Teknologi',
            'MM' => 'Kreatif',
            'OTKP' => 'Bisnis',
            'AKL' => 'Bisnis',
            'BDP' => 'Bisnis'
        ];

        return $kategoriMap[$kode] ?? 'Umum';
    }

    /**
     * Filter program studi berdasarkan kategori
     */
    public function filterByCategory($category)
    {
        $this->activeFilter = $category;
    }

    /**
     * Set filter untuk program studi (alias untuk filterByCategory)
     * Method ini dipanggil dari view untuk kompatibilitas
     */
    public function setFilter($category)
    {
        $this->filterByCategory($category);
    }

    /**
     * Tampilkan detail program studi
     */
    public function showDetail($programId)
    {
        $this->selectedProgram = collect($this->studyPrograms)
            ->firstWhere('id', $programId);
        $this->showModal = true;
    }

    /**
     * Tutup modal detail
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProgram = null;
    }

    /**
     * Computed property untuk program studi yang difilter
     */
    public function getFilteredProgramsProperty()
    {
        if ($this->activeFilter === 'all') {
            return $this->studyPrograms;
        }

        return collect($this->studyPrograms)
            ->filter(function ($program) {
                return $program['kategori'] === $this->activeFilter;
            })
            ->values();
    }

    /**
     * Render komponen
     */
    public function render()
    {
        return view('livewire.frontend.jurusan.index');
    }
}
