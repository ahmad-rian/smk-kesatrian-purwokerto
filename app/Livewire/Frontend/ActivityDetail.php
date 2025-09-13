<?php

namespace App\Livewire\Frontend;

use App\Models\SchoolActivity;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

/**
 * Komponen Livewire untuk menampilkan detail kegiatan sekolah
 * 
 * Fitur:
 * - Menampilkan informasi lengkap kegiatan
 * - Galeri foto kegiatan
 * - Kegiatan terkait
 * - SEO friendly dengan meta tags
 */
class ActivityDetail extends Component
{
    /**
     * ID kegiatan yang akan ditampilkan
     */
    public string $activityId;
    
    /**
     * Data kegiatan yang sedang ditampilkan
     */
    public ?SchoolActivity $activity = null;
    
    /**
     * Daftar kegiatan terkait
     */
    public $relatedActivities = [];

    /**
     * Mount component dengan parameter ID kegiatan
     */
    public function mount(string $id): void
    {
        $this->activityId = $id;
        $this->loadActivity();
        $this->loadRelatedActivities();
    }

    /**
     * Load data kegiatan berdasarkan ID
     */
    private function loadActivity(): void
    {
        $this->activity = Cache::remember(
            "activity_detail_{$this->activityId}",
            now()->addHours(1),
            fn() => SchoolActivity::find($this->activityId)
        );
        
        // Jika kegiatan tidak ditemukan, set activity ke null
        // Handling akan dilakukan di view
    }

    /**
     * Load kegiatan terkait (kategori sama, exclude current)
     */
    private function loadRelatedActivities(): void
    {
        if (!$this->activity) return;
        
        $this->relatedActivities = Cache::remember(
            "related_activities_{$this->activityId}",
            now()->addHours(1),
            fn() => SchoolActivity::where('kategori', $this->activity->kategori)
                ->where('id', '!=', $this->activityId)
                ->latest()
                ->take(3)
                ->get()
        );
    }

    /**
     * Render komponen
     */
    public function render()
    {
        return view('livewire.frontend.activity-detail');
    }
    
    /**
     * Get page title untuk SEO
     */
    public function getTitle(): string
    {
        return $this->activity ? $this->activity->nama_kegiatan : 'Detail Kegiatan';
    }
}