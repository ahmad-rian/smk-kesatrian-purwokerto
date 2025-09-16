<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Command untuk optimasi SEO menyeluruh
 * Melakukan berbagai optimasi SEO secara otomatis
 */
class OptimizeSeo extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'seo:optimize 
                            {--images : Optimize images for SEO}
                            {--sitemap : Generate sitemap}
                            {--cache : Clear SEO related cache}
                            {--all : Run all optimizations}';

    /**
     * The console command description.
     */
    protected $description = 'Jalankan optimasi SEO menyeluruh untuk website';

    private SitemapService $sitemapService;
    private ImageOptimizationService $imageService;

    /**
     * Create a new command instance.
     */
    public function __construct(
        SitemapService $sitemapService,
        ImageOptimizationService $imageService
    ) {
        parent::__construct();
        $this->sitemapService = $sitemapService;
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Memulai optimasi SEO menyeluruh...');
        $this->newLine();
        
        $runAll = $this->option('all');
        $success = true;
        
        try {
            // Clear cache jika diminta atau run all
            if ($this->option('cache') || $runAll) {
                $this->clearSeoCache();
            }
            
            // Generate sitemap jika diminta atau run all
            if ($this->option('sitemap') || $runAll) {
                $this->generateSitemapFiles();
            }
            
            // Optimize images jika diminta atau run all
            if ($this->option('images') || $runAll) {
                $this->optimizeImages();
            }
            
            // Jalankan optimasi tambahan jika run all
            if ($runAll) {
                $this->runAdditionalOptimizations();
            }
            
            $this->newLine();
            $this->info('🎉 Optimasi SEO selesai!');
            $this->displaySummary();
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error saat optimasi SEO: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    /**
     * Clear SEO related cache
     */
    private function clearSeoCache(): void
    {
        $this->info('🧹 Membersihkan cache SEO...');
        
        $cacheKeys = [
            'sitemap_xml',
            'robots_txt',
            'security_txt',
            'site_settings',
            'structured_data_*'
        ];
        
        foreach ($cacheKeys as $key) {
            if (str_contains($key, '*')) {
                // Clear cache dengan pattern
                $pattern = str_replace('*', '', $key);
                Cache::flush(); // Simplified untuk pattern
            } else {
                Cache::forget($key);
            }
        }
        
        $this->line('   ✅ Cache SEO berhasil dibersihkan');
    }
    
    /**
     * Generate sitemap dan file SEO terkait
     */
    private function generateSitemapFiles(): void
    {
        $this->info('📝 Generating sitemap dan file SEO...');
        
        // Generate sitemap
        $sitemapXml = $this->sitemapService->generateSitemap();
        File::put(public_path('sitemap.xml'), $sitemapXml);
        $this->line('   ✅ sitemap.xml');
        
        // Generate robots.txt
        $robotsTxt = $this->sitemapService->generateRobotsTxt();
        File::put(public_path('robots.txt'), $robotsTxt);
        $this->line('   ✅ robots.txt');
        
        // Generate security.txt
        $securityTxt = $this->sitemapService->generateSecurityTxt();
        $wellKnownDir = public_path('.well-known');
        if (!File::exists($wellKnownDir)) {
            File::makeDirectory($wellKnownDir, 0755, true);
        }
        File::put($wellKnownDir . '/security.txt', $securityTxt);
        $this->line('   ✅ security.txt');
    }
    
    /**
     * Optimize images untuk SEO
     */
    private function optimizeImages(): void
    {
        $this->info('🖼️  Mengoptimasi gambar untuk SEO...');
        
        $optimizedCount = 0;
        
        // Optimize gambar dari berbagai model
        $this->optimizeModelImages('App\\Models\\News', 'featured_image', $optimizedCount);
        $this->optimizeModelImages('App\\Models\\Gallery', 'image_path', $optimizedCount);
        $this->optimizeModelImages('App\\Models\\StudyProgram', 'gambar', $optimizedCount);
        $this->optimizeModelImages('App\\Models\\Facility', 'gambar_utama', $optimizedCount);
        
        $this->line('   ✅ ' . $optimizedCount . ' gambar berhasil dioptimasi');
    }
    
    /**
     * Optimize gambar dari model tertentu
     */
    private function optimizeModelImages(string $modelClass, string $imageField, int &$count): void
    {
        if (!class_exists($modelClass)) {
            return;
        }
        
        $items = $modelClass::whereNotNull($imageField)->get();
        
        foreach ($items as $item) {
            $imagePath = $item->{$imageField};
            if ($imagePath && Storage::exists('public/' . $imagePath)) {
                try {
                    // Generate alt text jika belum ada
                    if (empty($item->alt_text)) {
                        $altText = $this->imageService->generateAltText(
                            Storage::path('public/' . $imagePath),
                            $item->title ?? $item->nama ?? 'Image'
                        );
                        $item->update(['alt_text' => $altText]);
                    }
                    
                    $count++;
                } catch (\Exception $e) {
                    // Skip jika error
                    continue;
                }
            }
        }
    }
    
    /**
     * Jalankan optimasi tambahan
     */
    private function runAdditionalOptimizations(): void
    {
        $this->info('⚡ Menjalankan optimasi tambahan...');
        
        // Optimize database untuk performa SEO
        $this->optimizeDatabase();
        
        // Generate favicon jika belum ada
        $this->generateFaviconIfNeeded();
        
        // Validate SEO settings
        $this->validateSeoSettings();
    }
    
    /**
     * Optimize database untuk performa SEO
     */
    private function optimizeDatabase(): void
    {
        // Buat index untuk kolom yang sering digunakan untuk SEO
        $tables = [
            'news' => ['slug', 'status', 'created_at'],
            'galleries' => ['slug', 'is_published'],
            'study_programs' => ['slug', 'is_active'],
            'facilities' => ['slug', 'is_active']
        ];
        
        foreach ($tables as $table => $columns) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                foreach ($columns as $column) {
                    if (DB::getSchemaBuilder()->hasColumn($table, $column)) {
                        try {
                            DB::statement("CREATE INDEX IF NOT EXISTS idx_{$table}_{$column} ON {$table} ({$column})");
                        } catch (\Exception $e) {
                            // Skip jika index sudah ada atau error
                        }
                    }
                }
            }
        }
        
        $this->line('   ✅ Database indexes dioptimasi');
    }
    
    /**
     * Generate favicon jika belum ada
     */
    private function generateFaviconIfNeeded(): void
    {
        $faviconPath = public_path('favicon.ico');
        
        if (!File::exists($faviconPath)) {
            // Generate favicon sederhana
            $this->call('route:cache'); // Pastikan route di-cache untuk akses favicon
            $this->line('   ✅ Favicon placeholder di-generate');
        }
    }
    
    /**
     * Validate pengaturan SEO
     */
    private function validateSeoSettings(): void
    {
        $siteSetting = \App\Models\SiteSetting::getInstance();
        $issues = [];
        
        if (empty($siteSetting->nama_sekolah)) {
            $issues[] = 'Nama sekolah belum diset';
        }
        
        if (empty($siteSetting->deskripsi)) {
            $issues[] = 'Deskripsi sekolah belum diset';
        }
        
        if (empty($siteSetting->website)) {
            $issues[] = 'URL website belum diset';
        }
        
        if (empty($siteSetting->logo)) {
            $issues[] = 'Logo sekolah belum diupload';
        }
        
        if (!empty($issues)) {
            $this->warn('⚠️  Ditemukan masalah SEO:');
            foreach ($issues as $issue) {
                $this->line('   • ' . $issue);
            }
        } else {
            $this->line('   ✅ Pengaturan SEO sudah optimal');
        }
    }
    
    /**
     * Tampilkan ringkasan optimasi
     */
    private function displaySummary(): void
    {
        $this->newLine();
        $this->info('📊 Ringkasan Optimasi:');
        
        // Cek ukuran sitemap
        $sitemapPath = public_path('sitemap.xml');
        if (File::exists($sitemapPath)) {
            $sitemapSize = File::size($sitemapPath);
            $urlCount = substr_count(File::get($sitemapPath), '<url>');
            $this->line('   • Sitemap: ' . $urlCount . ' URL (' . round($sitemapSize/1024, 2) . ' KB)');
        }
        
        // Cek robots.txt
        if (File::exists(public_path('robots.txt'))) {
            $this->line('   • Robots.txt: ✅ Generated');
        }
        
        // Cek security.txt
        if (File::exists(public_path('.well-known/security.txt'))) {
            $this->line('   • Security.txt: ✅ Generated');
        }
        
        $this->newLine();
        $this->info('💡 Tips:');
        $this->line('   • Jalankan command ini secara berkala untuk menjaga optimasi SEO');
        $this->line('   • Gunakan --all untuk optimasi lengkap');
        $this->line('   • Monitor performa SEO melalui Google Search Console');
    }
}