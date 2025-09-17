<?php

namespace App\Livewire\Frontend;

use App\Services\WebsiteVisitorService;
use Livewire\Component;

class VisitorCounter extends Component
{
    public $todayVisitors = 0;
    public $totalVisitors = 0;

    public function mount()
    {
        $this->loadVisitorCounts();
    }

    public function loadVisitorCounts()
    {
        $visitorService = app(WebsiteVisitorService::class);
        $this->todayVisitors = $visitorService->getTodayVisitors();
        $this->totalVisitors = $visitorService->getTotalVisitors();
    }

    public function render()
    {
        return view('livewire.frontend.visitor-counter');
    }
}
