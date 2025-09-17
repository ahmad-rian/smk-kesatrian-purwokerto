<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class WebsiteVisitorService
{
    /**
     * Track website visitor by IP address (unique per day)
     */
    public function trackVisitor(): void
    {
        $ip = Request::ip();
        $today = Carbon::today()->toDateString();
        $cacheKey = "website_visitor_{$today}_{$ip}";

        // Check if this IP has already been counted today
        if (!Cache::has($cacheKey)) {
            // Store visitor data
            DB::table('website_visitors')->updateOrInsert(
                [
                    'ip_address' => $ip,
                    'visit_date' => $today,
                ],
                [
                    'ip_address' => $ip,
                    'visit_date' => $today,
                    'user_agent' => Request::userAgent(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Cache this IP for today (expires at midnight)
            $minutesUntilMidnight = Carbon::now()->diffInMinutes(Carbon::tomorrow());
            Cache::put($cacheKey, true, $minutesUntilMidnight);

            // Clear the cached count
            Cache::forget("website_visitors_count_{$today}");
        }
    }

    /**
     * Get today's unique visitors count
     */
    public function getTodayVisitors(): int
    {
        $today = Carbon::today()->toDateString();
        $cacheKey = "website_visitors_count_{$today}";

        return Cache::remember($cacheKey, 300, function () use ($today) {
            return DB::table('website_visitors')
                ->whereDate('visit_date', $today)
                ->distinct('ip_address')
                ->count();
        });
    }

    /**
     * Get total unique visitors (all time)
     */
    public function getTotalVisitors(): int
    {
        $cacheKey = "website_visitors_total";

        return Cache::remember($cacheKey, 3600, function () {
            return DB::table('website_visitors')
                ->distinct('ip_address')
                ->count();
        });
    }

    /**
     * Get visitors count for specific date range
     */
    public function getVisitorsInRange(Carbon $startDate, Carbon $endDate): int
    {
        return DB::table('website_visitors')
            ->whereBetween('visit_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->distinct('ip_address')
            ->count();
    }

    /**
     * Cleanup old visitor data (older than 30 days)
     */
    public function cleanupOldData(): int
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30)->toDateString();

        return DB::table('website_visitors')
            ->where('visit_date', '<', $thirtyDaysAgo)
            ->delete();
    }
}
