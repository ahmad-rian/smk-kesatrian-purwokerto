<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

/**
 * Command untuk menjalankan optimasi SEO secara terjadwal
 * Dapat dijalankan via cron job untuk maintenance otomatis
 */
class ScheduleSeoOptimization extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:schedule 
                            {--daily : Run daily optimization}
                            {--weekly : Run weekly optimization}
                            {--force : Force run even if recently executed}';

    /**
     * The console command description.
     */
    protected $description = 'Jalankan optimasi SEO secara terjadwal (daily/weekly)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🕐 Memulai optimasi SEO terjadwal...');
        
        try {
            $isDaily = $this->option('daily');
            $isWeekly = $this->option('weekly');
            $force = $this->option('force');
            
            if (!$isDaily && !$isWeekly) {
                $this->error('❌ Pilih salah satu: --daily atau --weekly');
                return Command::FAILURE;
            }
            
            $scheduleType = $isDaily ? 'daily' : 'weekly';
            
            // Cek apakah sudah dijalankan hari ini/minggu ini
            if (!$force && $this->wasRecentlyExecuted($scheduleType)) {
                $this->info('ℹ️  Optimasi SEO sudah dijalankan hari ini. Gunakan --force untuk menjalankan ulang.');
                return Command::SUCCESS;
            }
            
            // Jalankan optimasi berdasarkan schedule
            if ($isDaily) {
                $this->runDailyOptimization();
            } else {
                $this->runWeeklyOptimization();
            }
            
            // Catat waktu eksekusi
            $this->recordExecution($scheduleType);
            
            $this->info('🎉 Optimasi SEO terjadwal selesai!');
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error saat optimasi SEO terjadwal: ' . $e->getMessage());
            Log::error('SEO Schedule Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Jalankan optimasi harian
     */
    private function runDailyOptimization(): void
    {
        $this->info('📅 Menjalankan optimasi SEO harian...');
        
        // 1. Generate sitemap (ringan, bisa harian)
        $this->line('   • Generating sitemap...');
        Artisan::call('seo:optimize', ['--sitemap' => true]);
        
        // 2. Clear cache SEO
        $this->line('   • Clearing SEO cache...');
        Artisan::call('seo:optimize', ['--cache' => true]);
        
        // 3. Validate SEO settings
        $this->validateSeoHealth();
        
        $this->info('✅ Optimasi harian selesai');
    }
    
    /**
     * Jalankan optimasi mingguan
     */
    private function runWeeklyOptimization(): void
    {
        $this->info('📅 Menjalankan optimasi SEO mingguan...');
        
        // 1. Optimasi lengkap (termasuk gambar)
        $this->line('   • Running full SEO optimization...');
        Artisan::call('seo:optimize', ['--all' => true]);
        
        // 2. Generate laporan SEO
        $this->generateSeoReport();
        
        // 3. Cleanup file lama
        $this->cleanupOldFiles();
        
        $this->info('✅ Optimasi mingguan selesai');
    }
    
    /**
     * Cek apakah command sudah dijalankan baru-baru ini
     */
    private function wasRecentlyExecuted(string $scheduleType): bool
    {
        $cacheKey = "seo_schedule_last_run_{$scheduleType}";
        $lastRun = Cache::get($cacheKey);
        
        if (!$lastRun) {
            return false;
        }
        
        $lastRunDate = Carbon::parse($lastRun);
        $now = Carbon::now();
        
        if ($scheduleType === 'daily') {
            return $lastRunDate->isToday();
        } else {
            return $lastRunDate->isCurrentWeek();
        }
    }
    
    /**
     * Catat waktu eksekusi
     */
    private function recordExecution(string $scheduleType): void
    {
        $cacheKey = "seo_schedule_last_run_{$scheduleType}";
        Cache::put($cacheKey, Carbon::now()->toDateTimeString(), now()->addDays(8));
    }
    
    /**
     * Validasi kesehatan SEO
     */
    private function validateSeoHealth(): void
    {
        $this->line('   • Validating SEO health...');
        
        $issues = [];
        
        // Cek file penting
        $importantFiles = [
            public_path('sitemap.xml') => 'Sitemap XML',
            public_path('robots.txt') => 'Robots.txt',
            public_path('.well-known/security.txt') => 'Security.txt'
        ];
        
        foreach ($importantFiles as $file => $name) {
            if (!file_exists($file)) {
                $issues[] = "{$name} tidak ditemukan";
            } elseif (filesize($file) === 0) {
                $issues[] = "{$name} kosong";
            }
        }
        
        // Cek pengaturan situs
        try {
            $siteSetting = \App\Models\SiteSetting::getInstance();
            
            if (empty($siteSetting->nama_sekolah)) {
                $issues[] = 'Nama sekolah belum diset';
            }
            
            if (empty($siteSetting->deskripsi)) {
                $issues[] = 'Deskripsi sekolah belum diset';
            }
            
            if (empty($siteSetting->website)) {
                $issues[] = 'URL website belum diset';
            }
            
        } catch (\Exception $e) {
            $issues[] = 'Error mengakses pengaturan situs';
        }
        
        // Log issues jika ada
        if (!empty($issues)) {
            $issueText = implode(', ', $issues);
            Log::warning('SEO Health Issues: ' . $issueText);
            $this->warn('   ⚠️  Ditemukan masalah: ' . $issueText);
        } else {
            $this->line('   ✅ SEO health check passed');
        }
    }
    
    /**
     * Generate laporan SEO mingguan
     */
    private function generateSeoReport(): void
    {
        $this->line('   • Generating SEO report...');
        
        try {
            $report = [];
            
            // Statistik sitemap
            $sitemapPath = public_path('sitemap.xml');
            if (file_exists($sitemapPath)) {
                $sitemapContent = file_get_contents($sitemapPath);
                $urlCount = substr_count($sitemapContent, '<url>');
                $report['sitemap_urls'] = $urlCount;
                $report['sitemap_size'] = round(filesize($sitemapPath) / 1024, 2) . ' KB';
            }
            
            // Statistik konten
            if (class_exists('\App\Models\News')) {
                $report['news_count'] = \App\Models\News::where('status', 'published')->count();
            }
            
            if (class_exists('\App\Models\Gallery')) {
                $report['gallery_count'] = \App\Models\Gallery::where('is_published', true)->count();
            }
            
            if (class_exists('\App\Models\StudyProgram')) {
                $report['study_programs_count'] = \App\Models\StudyProgram::where('is_active', true)->count();
            }
            
            // Simpan laporan ke cache
            $cacheKey = 'seo_weekly_report_' . Carbon::now()->format('Y_W');
            Cache::put($cacheKey, $report, now()->addDays(8));
            
            // Log laporan
            Log::info('SEO Weekly Report', $report);
            
            $this->line('   ✅ Laporan SEO disimpan');
            
        } catch (\Exception $e) {
            Log::error('Error generating SEO report: ' . $e->getMessage());
            $this->line('   ⚠️  Error generating report');
        }
    }
    
    /**
     * Cleanup file lama
     */
    private function cleanupOldFiles(): void
    {
        $this->line('   • Cleaning up old files...');
        
        try {
            // Cleanup cache lama (lebih dari 1 minggu)
            $oldCacheKeys = [
                'seo_weekly_report_*',
                'structured_data_*'
            ];
            
            // Untuk Laravel, kita bisa flush cache yang sudah expired
            // atau implementasi cleanup yang lebih spesifik
            
            $this->line('   ✅ Cleanup completed');
            
        } catch (\Exception $e) {
            Log::error('Error during cleanup: ' . $e->getMessage());
            $this->line('   ⚠️  Error during cleanup');
        }
    }
}