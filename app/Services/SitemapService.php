<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\HomeCarousel;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

/**
 * Service untuk generate sitemap.xml dinamis
 * 
 * Service ini menghasilkan sitemap XML yang mencakup:
 * - Halaman statis (beranda, tentang, kontak, dll)
 * - Halaman dinamis (berita, galeri, jurusan)
 * - Prioritas dan frekuensi update yang sesuai
 * - Last modified date yang akurat
 */
class SitemapService
{
    /**
     * Generate sitemap XML lengkap
     */
    public function generateSitemap(): string
    {
        $siteSetting = SiteSetting::getInstance();
        $baseUrl = $siteSetting->website ?: URL::to('/');
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Halaman statis
        $xml .= $this->addStaticPages($baseUrl);
        
        // Halaman dinamis - Berita
        $xml .= $this->addBeritaPages($baseUrl);
        
        // Halaman dinamis - Galeri
        $xml .= $this->addGaleriPages($baseUrl);
        
        // Halaman dinamis - Jurusan
        $xml .= $this->addJurusanPages($baseUrl);
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Tambahkan halaman statis ke sitemap
     */
    private function addStaticPages(string $baseUrl): string
    {
        $staticPages = [
            // Halaman utama dengan prioritas tertinggi
            [
                'url' => $baseUrl,
                'lastmod' => now()->toISOString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            
            // Halaman profil sekolah
            [
                'url' => $baseUrl . '/profil',
                'lastmod' => $this->getLastModifiedDate('site_settings'),
                'changefreq' => 'monthly',
                'priority' => '0.9'
            ],
            
            // Halaman visi misi
            [
                'url' => $baseUrl . '/visi-misi',
                'lastmod' => $this->getLastModifiedDate('site_settings'),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            
            // Halaman jurusan (index)
            [
                'url' => $baseUrl . '/jurusan',
                'lastmod' => $this->getLastModifiedDate('jurusans'),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
            
            // Halaman berita (index)
            [
                'url' => $baseUrl . '/berita',
                'lastmod' => $this->getLastModifiedDate('beritas'),
                'changefreq' => 'daily',
                'priority' => '0.8'
            ],
            
            // Halaman galeri (index)
            [
                'url' => $baseUrl . '/galeri',
                'lastmod' => $this->getLastModifiedDate('galeris'),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            
            // Halaman fasilitas
            [
                'url' => $baseUrl . '/fasilitas',
                'lastmod' => now()->subDays(30)->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ],
            
            // Halaman kontak
            [
                'url' => $baseUrl . '/kontak',
                'lastmod' => $this->getLastModifiedDate('site_settings'),
                'changefreq' => 'monthly',
                'priority' => '0.6'
            ],
            
            // Halaman pendaftaran
            [
                'url' => $baseUrl . '/pendaftaran',
                'lastmod' => now()->subDays(7)->toISOString(),
                'changefreq' => 'weekly',
                'priority' => '0.9'
            ],
        ];
        
        $xml = '';
        foreach ($staticPages as $page) {
            $xml .= $this->createUrlEntry(
                $page['url'],
                $page['lastmod'],
                $page['changefreq'],
                $page['priority']
            );
        }
        
        return $xml;
    }
    
    /**
     * Tambahkan halaman berita ke sitemap
     */
    private function addBeritaPages(string $baseUrl): string
    {
        $xml = '';
        
        // Cek apakah model Berita ada
        if (class_exists('App\\Models\\Berita')) {
            try {
                $beritas = \App\Models\Berita::where('status', 'published')
                    ->orderBy('updated_at', 'desc')
                    ->limit(1000) // Batasi untuk performa
                    ->get(['slug', 'updated_at', 'created_at']);
                
                foreach ($beritas as $berita) {
                    $xml .= $this->createUrlEntry(
                        $baseUrl . '/berita/' . $berita->slug,
                        $berita->updated_at->toISOString(),
                        'monthly',
                        '0.6'
                    );
                }
            } catch (\Exception $e) {
                // Log error tapi lanjutkan proses
                Log::warning('Error adding berita to sitemap: ' . $e->getMessage());
            }
        }
        
        return $xml;
    }
    
    /**
     * Tambahkan halaman galeri ke sitemap
     */
    private function addGaleriPages(string $baseUrl): string
    {
        $xml = '';
        
        // Cek apakah model Galeri ada
        if (class_exists('App\\Models\\Galeri')) {
            try {
                $galeris = \App\Models\Galeri::where('status', 'published')
                    ->orderBy('updated_at', 'desc')
                    ->limit(500) // Batasi untuk performa
                    ->get(['slug', 'updated_at']);
                
                foreach ($galeris as $galeri) {
                    $xml .= $this->createUrlEntry(
                        $baseUrl . '/galeri/' . $galeri->slug,
                        $galeri->updated_at->toISOString(),
                        'monthly',
                        '0.5'
                    );
                }
            } catch (\Exception $e) {
                Log::warning('Error adding galeri to sitemap: ' . $e->getMessage());
            }
        }
        
        return $xml;
    }
    
    /**
     * Tambahkan halaman jurusan ke sitemap
     */
    private function addJurusanPages(string $baseUrl): string
    {
        $xml = '';
        
        // Cek apakah model Jurusan ada
        if (class_exists('App\\Models\\Jurusan')) {
            try {
                $jurusans = \App\Models\Jurusan::where('status', 'aktif')
                    ->orderBy('updated_at', 'desc')
                    ->get(['slug', 'updated_at']);
                
                foreach ($jurusans as $jurusan) {
                    $xml .= $this->createUrlEntry(
                        $baseUrl . '/jurusan/' . $jurusan->slug,
                        $jurusan->updated_at->toISOString(),
                        'monthly',
                        '0.8'
                    );
                }
            } catch (\Exception $e) {
                Log::warning('Error adding jurusan to sitemap: ' . $e->getMessage());
            }
        }
        
        return $xml;
    }
    
    /**
     * Buat entry URL untuk sitemap
     */
    private function createUrlEntry(string $url, string $lastmod, string $changefreq, string $priority): string
    {
        return "  <url>\n" .
               "    <loc>" . htmlspecialchars($url) . "</loc>\n" .
               "    <lastmod>" . $lastmod . "</lastmod>\n" .
               "    <changefreq>" . $changefreq . "</changefreq>\n" .
               "    <priority>" . $priority . "</priority>\n" .
               "  </url>\n";
    }
    
    /**
     * Dapatkan tanggal modifikasi terakhir dari tabel
     */
    private function getLastModifiedDate(string $table): string
    {
        try {
            $lastModified = DB::table($table)
                ->orderBy('updated_at', 'desc')
                ->value('updated_at');
            
            return $lastModified 
                ? Carbon::parse($lastModified)->toISOString()
                : now()->toISOString();
        } catch (\Exception $e) {
            return now()->toISOString();
        }
    }
    
    /**
     * Generate robots.txt yang optimal
     */
    public function generateRobotsTxt(): string
    {
        $siteSetting = SiteSetting::getInstance();
        $baseUrl = $siteSetting->website ?: URL::to('/');
        
        $robotsTxt = "# Robots.txt untuk " . $siteSetting->nama_sekolah . "\n";
        $robotsTxt .= "# Generated automatically\n\n";
        
        // Allow all bots untuk konten publik
        $robotsTxt .= "User-agent: *\n";
        $robotsTxt .= "Allow: /\n";
        $robotsTxt .= "Allow: /css/\n";
        $robotsTxt .= "Allow: /js/\n";
        $robotsTxt .= "Allow: /images/\n";
        $robotsTxt .= "Allow: /storage/\n";
        
        // Disallow admin dan private areas
        $robotsTxt .= "Disallow: /admin/\n";
        $robotsTxt .= "Disallow: /login\n";
        $robotsTxt .= "Disallow: /register\n";
        $robotsTxt .= "Disallow: /dashboard\n";
        $robotsTxt .= "Disallow: /api/\n";
        $robotsTxt .= "Disallow: /vendor/\n";
        $robotsTxt .= "Disallow: /*.pdf$\n";
        $robotsTxt .= "Disallow: /search?\n";
        
        // Crawl delay untuk menghindari overload
        $robotsTxt .= "Crawl-delay: 1\n\n";
        
        // Khusus untuk Google Bot
        $robotsTxt .= "User-agent: Googlebot\n";
        $robotsTxt .= "Allow: /\n";
        $robotsTxt .= "Crawl-delay: 0\n\n";
        
        // Khusus untuk Bing Bot
        $robotsTxt .= "User-agent: Bingbot\n";
        $robotsTxt .= "Allow: /\n";
        $robotsTxt .= "Crawl-delay: 1\n\n";
        
        // Sitemap location
        $robotsTxt .= "Sitemap: " . $baseUrl . "/sitemap.xml\n";
        
        return $robotsTxt;
    }
    
    /**
     * Generate security.txt untuk keamanan
     */
    public function generateSecurityTxt(): string
    {
        $siteSetting = SiteSetting::getInstance();
        
        $securityTxt = "# Security.txt untuk " . $siteSetting->nama_sekolah . "\n";
        $securityTxt .= "# Generated automatically\n\n";
        
        if ($siteSetting->email) {
            $securityTxt .= "Contact: mailto:" . $siteSetting->email . "\n";
        }
        
        $securityTxt .= "Expires: " . now()->addYear()->toISOString() . "\n";
        $securityTxt .= "Preferred-Languages: id, en\n";
        $securityTxt .= "Canonical: " . ($siteSetting->website ?: URL::to('/')) . "/.well-known/security.txt\n";
        
        return $securityTxt;
    }
}