<?php

namespace App\Services;

use App\Models\News;
use App\Models\NewsVisitorSummary;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Shetabit\Visitor\Models\Visit;

/**
 * Service untuk mengelola visitor tracking berita
 * Optimasi dengan batching dan caching untuk performa tinggi
 */
class NewsVisitorService
{
    /**
     * Track visitor untuk berita
     */
    public function trackVisitor(News $news, ?string $ip = null): void
    {
        $ip = $ip ?: request()->ip();
        $today = today()->toDateString();

        // Cache key untuk tracking visitor unik per hari
        $cacheKey = "news_visitor_{$news->id}_{$today}_{$ip}";

        // Jika IP sudah mengunjungi berita ini hari ini, skip tracking
        if (Cache::has($cacheKey)) {
            return;
        }

        // Mark IP sebagai sudah mengunjungi (cache untuk 24 jam)
        Cache::put($cacheKey, true, now()->endOfDay());

        // Update atau create summary untuk hari ini
        $this->updateDailySummary($news->id, $ip, $today);
    }

    /**
     * Update summary harian visitor
     */
    protected function updateDailySummary(int $newsId, string $ip, string $date): void
    {
        $summary = NewsVisitorSummary::firstOrCreate(
            [
                'news_id' => $newsId,
                'visit_date' => $date
            ],
            [
                'unique_visitors' => 0,
                'total_visits' => 0,
                'visitor_ips' => []
            ]
        );

        $visitorIps = $summary->visitor_ips ?: [];

        // Jika IP belum ada di array hari ini
        if (!in_array($ip, $visitorIps)) {
            $visitorIps[] = $ip;
            $summary->unique_visitors++;
        }

        $summary->total_visits++;
        $summary->visitor_ips = $visitorIps;
        $summary->save();
    }

    /**
     * Get total visitor count untuk berita (semua waktu)
     */
    public function getTotalVisitors(News $news): int
    {
        // Cache result untuk 5 menit
        return Cache::remember(
            "news_total_visitors_{$news->id}",
            300, // 5 minutes
            function () use ($news) {
                // Hitung dari Visit model (visitor package)
                $packageCount = Visit::where('visitable_type', get_class($news))
                    ->where('visitable_id', $news->id)
                    ->count();

                $summaryCount = NewsVisitorSummary::where('news_id', $news->id)
                    ->sum('unique_visitors');

                // Return yang lebih besar (karena ada transisi dari sistem lama ke baru)
                return max($packageCount, $summaryCount);
            }
        );
    }

    /**
     * Get visitor count untuk periode tertentu
     */
    public function getVisitorsByPeriod(News $news, string $startDate, string $endDate): array
    {
        return NewsVisitorSummary::where('news_id', $news->id)
            ->dateRange($startDate, $endDate)
            ->orderBy('visit_date')
            ->get()
            ->map(function ($summary) {
                return [
                    'date' => $summary->visit_date->format('Y-m-d'),
                    'unique_visitors' => $summary->unique_visitors,
                    'total_visits' => $summary->total_visits
                ];
            })
            ->toArray();
    }

    /**
     * Get visitor stats untuk dashboard
     */
    public function getVisitorStats(News $news): array
    {
        $today = NewsVisitorSummary::where('news_id', $news->id)->today()->first();
        $yesterday = NewsVisitorSummary::where('news_id', $news->id)
            ->where('visit_date', today()->subDay())
            ->first();

        $thisWeek = NewsVisitorSummary::where('news_id', $news->id)
            ->dateRange(today()->startOfWeek(), today()->endOfWeek())
            ->sum('unique_visitors');

        $thisMonth = NewsVisitorSummary::where('news_id', $news->id)
            ->dateRange(today()->startOfMonth(), today()->endOfMonth())
            ->sum('unique_visitors');

        return [
            'total' => $this->getTotalVisitors($news),
            'today' => $today ? $today->unique_visitors : 0,
            'yesterday' => $yesterday ? $yesterday->unique_visitors : 0,
            'this_week' => $thisWeek,
            'this_month' => $thisMonth,
        ];
    }

    /**
     * Clean up old visitor IPs (untuk privacy dan storage optimization)
     * Jalankan via scheduled job
     */
    public function cleanupOldData(int $daysToKeepIps = 30): void
    {
        $cutoffDate = today()->subDays($daysToKeepIps);

        // Hapus IP data lama tapi pertahankan count
        NewsVisitorSummary::where('visit_date', '<', $cutoffDate)
            ->update(['visitor_ips' => null]);
    }

    /**
     * Get most visited news
     */
    public function getMostVisitedNews(int $limit = 10, int $days = 30): array
    {
        $startDate = today()->subDays($days);

        return NewsVisitorSummary::with('news')
            ->dateRange($startDate, today())
            ->selectRaw('news_id, SUM(unique_visitors) as total_visitors')
            ->groupBy('news_id')
            ->orderByDesc('total_visitors')
            ->limit($limit)
            ->get()
            ->map(function ($summary) {
                return [
                    'news' => $summary->news,
                    'visitors' => $summary->total_visitors
                ];
            })
            ->toArray();
    }
}
