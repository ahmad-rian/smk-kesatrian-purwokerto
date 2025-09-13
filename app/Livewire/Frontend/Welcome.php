<?php

namespace App\Livewire\Frontend;

use App\Models\HomeCarousel;
use App\Models\SchoolActivity;
use App\Models\StudyProgram;
use App\Models\SiteSetting;
use App\Models\News;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Cache;

/**
 * Komponen Livewire untuk halaman welcome/beranda
 * 
 * Menampilkan:
 * - Carousel gambar dari database
 * - Kegiatan sekolah dengan filter kategori
 * - Daftar program studi
 * - Footer dinamis dari site settings
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Title('Beranda - SMK Kesatrian')]
#[Layout('components.layouts.frontend')]
class Welcome extends Component
{
    /**
     * Filter kategori kegiatan yang dipilih
     */
    public string $selectedCategory = 'semua';

    /**
     * Daftar kategori kegiatan yang tersedia
     */
    public array $categories = [];

    /**
     * Mount component - inisialisasi data
     */
    public function mount(): void
    {
        $this->loadCategories();
    }

    /**
     * Load kategori kegiatan dari database
     */
    private function loadCategories(): void
    {
        $this->categories = Cache::remember('school_activity_categories', 3600, function () {
            $categories = SchoolActivity::where('aktif', true)
                ->whereNotNull('kategori')
                ->distinct()
                ->pluck('kategori')
                ->filter()
                ->sort()
                ->values()
                ->toArray();

            return array_merge(['semua'], $categories);
        });
    }

    /**
     * Filter kegiatan berdasarkan kategori
     */
    public function filterByCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    /**
     * Computed property untuk mendapatkan data carousel
     */
    public function getCarouselDataProperty()
    {
        return Cache::remember('home_carousel_data', 1800, function () {
            return HomeCarousel::where('aktif', true)
                ->orderBy('urutan')
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    /**
     * Computed property untuk mendapatkan kegiatan sekolah
     */
    public function getSchoolActivitiesProperty()
    {
        $cacheKey = 'school_activities_' . $this->selectedCategory;

        return Cache::remember($cacheKey, 1800, function () {
            $query = SchoolActivity::where('aktif', true)
                ->orderBy('unggulan', 'desc')
                ->orderBy('tanggal_mulai', 'desc');

            if ($this->selectedCategory !== 'semua') {
                $query->where('kategori', $this->selectedCategory);
            }

            return $query->limit(12)->get();
        });
    }

    /**
     * Computed property untuk mendapatkan program studi
     */
    public function getStudyProgramsProperty()
    {
        return Cache::remember('study_programs_active', 3600, function () {
            return StudyProgram::where('aktif', true)
                ->orderBy('urutan')
                ->orderBy('nama')
                ->get();
        });
    }

    /**
     * Computed property untuk mendapatkan site settings
     */
    public function getSiteSettingsProperty()
    {
        return Cache::remember('site_settings_data', 3600, function () {
            return SiteSetting::first();
        });
    }

    /**
     * Computed property untuk mendapatkan berita terbaru
     */
    public function getLatestNewsProperty()
    {
        return Cache::remember(
            'latest_news',
            now()->addMinutes(30),
            fn() => News::published()
                ->latest('tanggal_publikasi')
                ->take(4)
                ->get(['id', 'judul', 'slug', 'ringkasan', 'gambar', 'tanggal_publikasi', 'kategori'])
        );
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.frontend.welcome', [
            'carouselData' => $this->carouselData,
            'schoolActivities' => $this->schoolActivities,
            'studyPrograms' => $this->studyPrograms,
            'siteSettings' => $this->siteSettings,
            'latestNews' => $this->latestNews,
            'categories' => $this->categories,
            'selectedCategory' => $this->selectedCategory,
        ]);
    }
}
