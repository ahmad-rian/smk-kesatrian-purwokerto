<?php

namespace App\Livewire\Frontend\Fasilitas;

use App\Models\Facility;
use App\Models\StudyProgram;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Komponen Livewire untuk halaman fasilitas sekolah
 * 
 * Menampilkan daftar fasilitas dengan filter kategori dan program studi
 * Menggunakan desain grid dengan card interaktif dan modal detail
 * 
 * @package App\Livewire\Frontend\Fasilitas
 */
#[Title('Fasilitas - SMK Kesatrian')]
#[Layout('components.layouts.frontend')]
class Index extends Component
{
    /**
     * Daftar fasilitas yang akan ditampilkan
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $facilities;

    /**
     * Kategori yang dipilih untuk filter
     * 
     * @var string
     */
    public string $selectedCategory = 'all';

    /**
     * Program studi yang dipilih untuk filter
     * 
     * @var string
     */
    public string $selectedProgram = 'all';



    /**
     * Daftar kategori fasilitas yang tersedia
     * 
     * @var array
     */
    public array $categories = [
        'all' => 'Semua Kategori',
        'Laboratorium' => 'Laboratorium',
        'Fasilitas Umum' => 'Fasilitas Umum',
        'Olahraga' => 'Olahraga',
        'Teknologi' => 'Teknologi',
        'Penunjang' => 'Penunjang'
    ];

    /**
     * Daftar program studi untuk filter
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $studyPrograms;

    /**
     * Inisialisasi komponen saat pertama kali dimuat
     * 
     * @return void
     */
    public function mount(): void
    {
        $this->loadFacilities();
        $this->loadStudyPrograms();
    }

    /**
     * Memuat data fasilitas dari database
     * 
     * @return void
     */
    public function loadFacilities(): void
    {
        $query = Facility::with(['studyProgram', 'images'])
            ->where('aktif', true)
            ->orderBy('urutan')
            ->orderBy('nama');

        // Filter berdasarkan kategori
        if ($this->selectedCategory !== 'all') {
            $query->where('kategori', $this->selectedCategory);
        }

        // Filter berdasarkan program studi
        if ($this->selectedProgram !== 'all') {
            $query->where('study_program_id', $this->selectedProgram);
        }

        $this->facilities = $query->get();
    }

    /**
     * Memuat data program studi untuk filter
     * 
     * @return void
     */
    public function loadStudyPrograms(): void
    {
        $this->studyPrograms = StudyProgram::where('aktif', true)
            ->orderBy('nama')
            ->get();
    }

    /**
     * Filter fasilitas berdasarkan kategori
     * 
     * @param string $category Kategori yang dipilih
     * @return void
     */
    public function filterByCategory(string $category): void
    {
        $this->selectedCategory = $category;
        $this->loadFacilities();
    }

    /**
     * Filter fasilitas berdasarkan program studi
     * 
     * @param string $program ID program studi yang dipilih
     * @return void
     */
    public function filterByProgram(string $program): void
    {
        $this->selectedProgram = $program;
        $this->loadFacilities();
    }



    /**
     * Render komponen
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.frontend.fasilitas.index');
    }
}
