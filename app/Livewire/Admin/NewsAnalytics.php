<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\News;
use App\Services\NewsVisitorService;
use Carbon\Carbon;

/**
 * Component untuk analytics berita di admin
 */
class NewsAnalytics extends Component
{
    public $selectedPeriod = '7'; // days
    public $chartData = [];
    public $topNews = [];
    public $totalStats = [];

    protected NewsVisitorService $visitorService;

    public function mount()
    {
        $this->visitorService = app(NewsVisitorService::class);
        $this->loadAnalytics();
    }

    public function updatedSelectedPeriod()
    {
        $this->loadAnalytics();
    }

    public function loadAnalytics()
    {
        $this->loadChartData();
        $this->loadTopNews();
        $this->loadTotalStats();
    }

    protected function loadChartData()
    {
        $startDate = Carbon::today()->subDays($this->selectedPeriod);
        $endDate = Carbon::today();

        $this->chartData = [];

        // Get daily visitor data for all news
        for ($date = $startDate; $date <= $endDate; $date->addDay()) {
            $dailyCount = \App\Models\NewsVisitorSummary::where('visit_date', $date->toDateString())
                ->sum('unique_visitors');

            $this->chartData[] = [
                'date' => $date->format('M j'),
                'visitors' => $dailyCount
            ];
        }
    }

    protected function loadTopNews()
    {
        $this->topNews = $this->visitorService->getMostVisitedNews(5, $this->selectedPeriod);
    }

    protected function loadTotalStats()
    {
        $this->totalStats = [
            'total_news' => News::where('status', 'published')->count(),
            'total_visitors' => \App\Models\NewsVisitorSummary::sum('unique_visitors'),
            'today_visitors' => \App\Models\NewsVisitorSummary::where('visit_date', today())->sum('unique_visitors'),
            'avg_daily' => round(\App\Models\NewsVisitorSummary::whereDate('visit_date', '>=', Carbon::today()->subDays(30))
                ->avg('unique_visitors') ?? 0)
        ];
    }

    public function render()
    {
        return view('livewire.admin.news-analytics');
    }
}
