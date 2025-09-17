<?php

namespace App\Livewire\Frontend\Berita;

use Livewire\Component;
use App\Models\News;
use App\Services\NewsVisitorService;

/**
 * Component untuk menampilkan statistik visitor berita
 */
class VisitorStats extends Component
{
    public News $news;
    public $stats = [];

    protected NewsVisitorService $visitorService;

    public function mount(News $news)
    {
        $this->news = $news;
        $this->visitorService = app(NewsVisitorService::class);
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = $this->visitorService->getVisitorStats($this->news);
    }

    public function render()
    {
        return view('livewire.frontend.berita.visitor-stats');
    }
}
