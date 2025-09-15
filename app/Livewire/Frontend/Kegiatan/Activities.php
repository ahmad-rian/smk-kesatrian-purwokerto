<?php

namespace App\Livewire\Frontend\Kegiatan;

use App\Models\SchoolActivity;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

/**
 * Livewire Component untuk Halaman Kegiatan Frontend
 * 
 * Fitur:
 * - Listing kegiatan dengan pagination
 * - Search kegiatan berdasarkan nama dan deskripsi
 * - Filter berdasarkan kategori
 * - Filter berdasarkan status aktif
 * - Responsive design
 * - SEO friendly
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Title('Kegiatan Sekolah - SMK Kesatrian')]
#[Layout('components.layouts.frontend')]
class Activities extends Component
{
    use WithPagination;

    /**
     * Properties untuk filter dan search
     */
    public string $search = '';
    public string $selectedCategory = 'semua';
    public int $perPage = 12;

    /**
     * Daftar kategori kegiatan yang tersedia
     */
    public array $categories = [];

    /**
     * Mount component dan load data kategori
     */
    public function mount(): void
    {
        $this->loadCategories();
    }

    /**
     * Reset pagination ketika search berubah
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination ketika kategori berubah
     */
    public function updatingSelectedCategory(): void
    {
        $this->resetPage();
    }

    /**
     * Load kategori kegiatan yang tersedia
     */
    private function loadCategories(): void
    {
        $this->categories = SchoolActivity::where('aktif', true)
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori')
            ->sort()
            ->values()
            ->toArray() ?? [];
    }

    /**
     * Get kegiatan dengan filter dan pagination
     */
    public function getActivitiesProperty()
    {
        return SchoolActivity::where('aktif', true)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama_kegiatan', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhere('lokasi', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->selectedCategory !== 'semua', function ($query) {
                $query->where('kategori', $this->selectedCategory);
            })
            ->orderBy('tanggal_mulai', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    /**
     * Get excerpt dari deskripsi kegiatan
     */
    public function getExcerpt(string $content, int $limit = 150): string
    {
        return \Illuminate\Support\Str::limit(strip_tags($content), $limit);
    }

    /**
     * Format tanggal untuk tampilan
     */
    public function formatDate($date): string
    {
        if (!$date) return '';

        return \Carbon\Carbon::parse($date)->format('d M Y');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.frontend.kegiatan.activities', [
            'activities' => $this->activities,
            'categories' => $this->categories ?? [],
            'selectedCategory' => $this->selectedCategory,
        ]);
    }
}
