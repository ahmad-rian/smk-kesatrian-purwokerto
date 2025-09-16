<?php

namespace App\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Command untuk generate sitemap.xml secara otomatis
 * Dapat dijadwalkan untuk update berkala
 */
class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sitemap:generate 
                            {--force : Force regenerate sitemap even if exists}
                            {--output= : Output directory (default: public)}';

    /**
     * The console command description.
     */
    protected $description = 'Generate sitemap.xml untuk SEO optimization';

    private SitemapService $sitemapService;

    /**
     * Create a new command instance.
     */
    public function __construct(SitemapService $sitemapService)
    {
        parent::__construct();
        $this->sitemapService = $sitemapService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🚀 Memulai generate sitemap...');
        
        $outputDir = $this->option('output') ?: public_path();
        $force = $this->option('force');
        
        try {
            // Cek apakah sitemap sudah ada
            $sitemapPath = $outputDir . '/sitemap.xml';
            
            if (File::exists($sitemapPath) && !$force) {
                if (!$this->confirm('Sitemap sudah ada. Apakah Anda ingin menimpa?')) {
                    $this->info('❌ Generate sitemap dibatalkan.');
                    return Command::FAILURE;
                }
            }
            
            // Generate sitemap utama
            $this->info('📝 Generating main sitemap...');
            $sitemapXml = $this->sitemapService->generateSitemap();
            
            // Simpan sitemap
            File::put($sitemapPath, $sitemapXml);
            $this->info('✅ Main sitemap berhasil disimpan: ' . $sitemapPath);
            
            // Generate robots.txt
            $this->info('🤖 Generating robots.txt...');
            $robotsTxt = $this->sitemapService->generateRobotsTxt();
            File::put($outputDir . '/robots.txt', $robotsTxt);
            $this->info('✅ Robots.txt berhasil disimpan.');
            
            // Generate security.txt
            $this->info('🔒 Generating security.txt...');
            $securityTxt = $this->sitemapService->generateSecurityTxt();
            
            // Buat direktori .well-known jika belum ada
            $wellKnownDir = $outputDir . '/.well-known';
            if (!File::exists($wellKnownDir)) {
                File::makeDirectory($wellKnownDir, 0755, true);
            }
            
            File::put($wellKnownDir . '/security.txt', $securityTxt);
            $this->info('✅ Security.txt berhasil disimpan.');
            
            // Statistik sitemap
            $this->displaySitemapStats($sitemapXml);
            
            $this->info('🎉 Semua file SEO berhasil di-generate!');
            $this->newLine();
            $this->info('📋 File yang di-generate:');
            $this->line('   • sitemap.xml');
            $this->line('   • robots.txt');
            $this->line('   • .well-known/security.txt');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error saat generate sitemap: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return Command::FAILURE;
        }
    }
    
    /**
     * Tampilkan statistik sitemap
     */
    private function displaySitemapStats(string $sitemapXml): void
    {
        $urlCount = substr_count($sitemapXml, '<url>');
        $sitemapSize = strlen($sitemapXml);
        $sitemapSizeKb = round($sitemapSize / 1024, 2);
        
        $this->newLine();
        $this->info('📊 Statistik Sitemap:');
        $this->line('   • Total URL: ' . $urlCount);
        $this->line('   • Ukuran file: ' . $sitemapSizeKb . ' KB');
        
        // Peringatan jika terlalu besar
        if ($sitemapSize > 50 * 1024 * 1024) { // 50MB
            $this->warn('⚠️  Peringatan: Sitemap terlalu besar (>50MB). Pertimbangkan untuk membagi menjadi beberapa sitemap.');
        }
        
        if ($urlCount > 50000) {
            $this->warn('⚠️  Peringatan: Terlalu banyak URL (>50,000). Pertimbangkan untuk membagi menjadi beberapa sitemap.');
        }
    }
}