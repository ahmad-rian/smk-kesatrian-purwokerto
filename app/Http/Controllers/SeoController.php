<?php

namespace App\Http\Controllers;

use App\Services\SitemapService;
use App\Services\SitemapGenerator;
use App\Services\RobotsGenerator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Controller untuk menangani SEO-related endpoints
 * 
 * Menangani:
 * - Sitemap.xml dinamis
 * - Robots.txt optimal
 * - Security.txt
 * - Meta tags untuk sharing
 */
class SeoController extends Controller
{
    /**
     * Service instances
     */
    protected SitemapService $sitemapService;
    protected SitemapGenerator $sitemapGenerator;
    protected RobotsGenerator $robotsGenerator;
    
    /**
     * Constructor
     */
    public function __construct(
        SitemapService $sitemapService,
        SitemapGenerator $sitemapGenerator,
        RobotsGenerator $robotsGenerator
    ) {
        $this->sitemapService = $sitemapService;
        $this->sitemapGenerator = $sitemapGenerator;
        $this->robotsGenerator = $robotsGenerator;
    }
    
    /**
     * Generate dan return sitemap.xml
     */
    public function sitemap(): Response
    {
        $sitemapXml = $this->sitemapService->generateSitemap();
        
        return response($sitemapXml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=3600'); // Cache 1 jam
    }
    
    /**
     * Generate dan return robots.txt
     */
    public function robots(): Response
    {
        $robotsTxt = $this->sitemapService->generateRobotsTxt();
        
        return response($robotsTxt, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache 24 jam
    }
    
    /**
     * Generate dan return security.txt
     */
    public function security(): Response
    {
        $securityTxt = $this->sitemapService->generateSecurityTxt();
        
        return response($securityTxt, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400'); // Cache 24 jam
    }
    
    /**
     * Generate manifest.json untuk PWA
     */
    public function manifest(): JsonResponse
    {
        $siteSetting = \App\Models\SiteSetting::getInstance();
        
        $manifest = [
            'name' => $siteSetting->nama_sekolah,
            'short_name' => $siteSetting->nama_singkat ?: $siteSetting->nama_sekolah,
            'description' => $siteSetting->deskripsi ?: 'SMK terbaik dengan pendidikan berkualitas',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#1e40af',
            'orientation' => 'portrait-primary',
            'scope' => '/',
            'lang' => 'id',
            'dir' => 'ltr',
            'categories' => ['education', 'school'],
            'icons' => []
        ];
        
        // Tambahkan icon jika logo tersedia
        if ($siteSetting->logo_url) {
            $logoUrl = url($siteSetting->logo_url);
            $manifest['icons'] = [
                [
                    'src' => $logoUrl,
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ],
                [
                    'src' => $logoUrl,
                    'sizes' => '512x512',
                    'type' => 'image/png',
                    'purpose' => 'any maskable'
                ]
            ];
        }
        
        return response()->json($manifest)
            ->header('Cache-Control', 'public, max-age=86400'); // Cache 24 jam
    }
    
    /**
     * Generate OpenSearch description untuk search engine
     */
    public function opensearch(): Response
    {
        $siteSetting = \App\Models\SiteSetting::getInstance();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">' . "\n";
        $xml .= '  <ShortName>' . htmlspecialchars($siteSetting->nama_singkat ?: $siteSetting->nama_sekolah) . '</ShortName>' . "\n";
        $xml .= '  <Description>Pencarian di ' . htmlspecialchars($siteSetting->nama_sekolah) . '</Description>' . "\n";
        $xml .= '  <Url type="text/html" template="' . url('/search?q={searchTerms}') . '"/>' . "\n";
        $xml .= '  <Language>id-ID</Language>' . "\n";
        $xml .= '  <OutputEncoding>UTF-8</OutputEncoding>' . "\n";
        $xml .= '  <InputEncoding>UTF-8</InputEncoding>' . "\n";
        
        if ($siteSetting->logo_url) {
            $xml .= '  <Image height="16" width="16" type="image/x-icon">' . url($siteSetting->logo_url) . '</Image>' . "\n";
        }
        
        $xml .= '</OpenSearchDescription>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/opensearchdescription+xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400');
    }
    
    /**
     * Generate browserconfig.xml untuk Windows tiles
     */
    public function browserconfig(): Response
    {
        $siteSetting = \App\Models\SiteSetting::getInstance();
        
        $xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $xml .= '<browserconfig>' . "\n";
        $xml .= '  <msapplication>' . "\n";
        $xml .= '    <tile>' . "\n";
        $xml .= '      <square150x150logo src="' . ($siteSetting->logo_url ? url($siteSetting->logo_url) : '/favicon.ico') . '"/>' . "\n";
        $xml .= '      <TileColor>#1e40af</TileColor>' . "\n";
        $xml .= '    </tile>' . "\n";
        $xml .= '  </msapplication>' . "\n";
        $xml .= '</browserconfig>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400');
    }
    
    /**
     * Generate humans.txt untuk credits
     */
    public function humans(): Response
    {
        $siteSetting = \App\Models\SiteSetting::getInstance();
        
        $humansTxt = "/* TEAM */\n";
        $humansTxt .= "Developer: Laravel Expert Agent\n";
        $humansTxt .= "Contact: " . ($siteSetting->email ?: 'info@example.com') . "\n";
        $humansTxt .= "Location: " . ($siteSetting->alamat ?: 'Indonesia') . "\n\n";
        
        $humansTxt .= "/* SITE */\n";
        $humansTxt .= "Last update: " . now()->format('Y/m/d') . "\n";
        $humansTxt .= "Language: Indonesian\n";
        $humansTxt .= "Doctype: HTML5\n";
        $humansTxt .= "IDE: Trae AI\n";
        $humansTxt .= "Framework: Laravel 12, Livewire 3, Alpine.js, MaryUI\n";
        $humansTxt .= "Fonts: Bricolage Grotesque, Inter, Poppins\n";
        
        return response($humansTxt, 200)
            ->header('Content-Type', 'text/plain; charset=utf-8')
            ->header('Cache-Control', 'public, max-age=86400');
    }
    
    /**
     * Generate favicon dari logo situs
     */
    public function favicon(Request $request): Response|BinaryFileResponse
    {
        $size = $request->get('size', 32);
        $siteSetting = \App\Models\SiteSetting::getInstance();
        
        if (!$siteSetting || !$siteSetting->logo) {
            // Return default favicon jika tidak ada logo
            $defaultFavicon = public_path('favicon.ico');
            if (file_exists($defaultFavicon)) {
                return response()->file($defaultFavicon);
            }
            
            // Generate simple favicon jika tidak ada file default
            return $this->generateDefaultFavicon($size);
        }
        
        $logoPath = storage_path('app/public/' . $siteSetting->logo);
        
        if (!file_exists($logoPath)) {
            return $this->generateDefaultFavicon($size);
        }
        
        try {
            // Generate favicon dari logo
            $faviconContent = $this->generateFaviconFromLogo($logoPath, $size);
            
            return response($faviconContent)
                ->header('Content-Type', 'image/png')
                ->header('Cache-Control', 'public, max-age=86400'); // Cache 1 hari
                
        } catch (\Exception $e) {
            Log::error('Favicon generation failed: ' . $e->getMessage());
            return $this->generateDefaultFavicon($size);
        }
    }
    
    /**
     * Generate favicon dari logo menggunakan GD
     */
    private function generateFaviconFromLogo(string $logoPath, int $size): string
    {
        $extension = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        
        // Load image berdasarkan type
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $sourceImage = imagecreatefromjpeg($logoPath);
                break;
            case 'png':
                $sourceImage = imagecreatefrompng($logoPath);
                break;
            case 'gif':
                $sourceImage = imagecreatefromgif($logoPath);
                break;
            default:
                throw new \Exception('Unsupported image format');
        }
        
        if (!$sourceImage) {
            throw new \Exception('Failed to load source image');
        }
        
        // Get original dimensions
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);
        
        // Create new image dengan ukuran favicon
        $favicon = imagecreatetruecolor($size, $size);
        
        // Enable alpha blending untuk transparency
        imagealphablending($favicon, false);
        imagesavealpha($favicon, true);
        
        // Set transparent background
        $transparent = imagecolorallocatealpha($favicon, 0, 0, 0, 127);
        imagefill($favicon, 0, 0, $transparent);
        
        // Resize dan copy image
        imagecopyresampled(
            $favicon, $sourceImage,
            0, 0, 0, 0,
            $size, $size,
            $originalWidth, $originalHeight
        );
        
        // Generate PNG content
        ob_start();
        imagepng($favicon, null, 9); // Max compression
        $content = ob_get_contents();
        ob_end_clean();
        
        // Cleanup
        imagedestroy($sourceImage);
        imagedestroy($favicon);
        
        return $content;
    }
    
    /**
     * Generate default favicon jika tidak ada logo
     */
    private function generateDefaultFavicon(int $size): Response
    {
        // Create simple colored favicon
        $favicon = imagecreatetruecolor($size, $size);
        
        // SMK Kesatrian brand colors (contoh: biru)
        $bgColor = imagecolorallocate($favicon, 59, 130, 246); // Blue-500
        $textColor = imagecolorallocate($favicon, 255, 255, 255); // White
        
        // Fill background
        imagefill($favicon, 0, 0, $bgColor);
        
        // Add simple text "SK" untuk SMK Kesatrian
        if ($size >= 16) {
            $fontSize = max(1, intval($size / 8));
            $text = 'SK';
            
            // Calculate text position (center) - simplified for built-in font
            $textWidth = strlen($text) * 6; // Approximate width for built-in font
            $textHeight = 10; // Approximate height for built-in font
            
            $x = ($size - $textWidth) / 2;
            $y = ($size + $textHeight) / 2;
            
            // Use built-in font jika TTF tidak tersedia
            imagestring($favicon, 3, $x, $y - 8, $text, $textColor);
        }
        
        // Generate PNG content
        ob_start();
        imagepng($favicon, null, 9);
        $content = ob_get_contents();
        ob_end_clean();
        
        imagedestroy($favicon);
        
        return response($content)
            ->header('Content-Type', 'image/png')
            ->header('Cache-Control', 'public, max-age=86400');
    }
    
    /**
     * Serve sitemap-pages.xml
     */
    public function sitemapPages(Request $request): Response
    {
        return $this->serveSitemapFile('sitemap-pages.xml', function () {
            return $this->sitemapGenerator->generatePagesSitemap();
        });
    }
    
    /**
     * Serve sitemap-news.xml
     */
    public function sitemapNews(Request $request): Response
    {
        return $this->serveSitemapFile('sitemap-news.xml', function () {
            return $this->sitemapGenerator->generateNewsSitemap();
        });
    }
    
    /**
     * Serve sitemap-activities.xml
     */
    public function sitemapActivities(Request $request): Response
    {
        return $this->serveSitemapFile('sitemap-activities.xml', function () {
            return $this->sitemapGenerator->generateActivitiesSitemap();
        });
    }
    
    /**
     * Serve sitemap-galleries.xml
     */
    public function sitemapGalleries(Request $request): Response
    {
        return $this->serveSitemapFile('sitemap-galleries.xml', function () {
            return $this->sitemapGenerator->generateGalleriesSitemap();
        });
    }
    
    /**
     * Generate sitemap manually (admin endpoint)
     */
    public function generateSitemap(Request $request): JsonResponse
    {
        try {
            $success = $this->sitemapGenerator->generateIndex();
            
            if ($success) {
                $urlsCount = $this->sitemapGenerator->getTotalUrlsCount();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sitemap berhasil di-generate',
                    'urls_count' => $urlsCount,
                    'generated_at' => now()->toISOString()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate sitemap'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Manual sitemap generation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Generate robots.txt manually (admin endpoint)
     */
    public function generateRobots(Request $request): JsonResponse
    {
        try {
            $success = $this->robotsGenerator->generate();
            
            if ($success) {
                $fileInfo = $this->robotsGenerator->getFileInfo();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Robots.txt berhasil di-generate',
                    'file_info' => $fileInfo,
                    'generated_at' => now()->toISOString()
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate robots.txt'
            ], 500);
            
        } catch (\Exception $e) {
            Log::error('Manual robots.txt generation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get SEO status info
     */
    public function status(Request $request): JsonResponse
    {
        try {
            $sitemapInfo = $this->sitemapGenerator->getFileInfo();
            $robotsInfo = $this->robotsGenerator->getFileInfo();
            $urlsCount = $this->sitemapGenerator->getTotalUrlsCount();
            
            return response()->json([
                'sitemap' => [
                    'files' => $sitemapInfo,
                    'total_urls' => $urlsCount,
                    'last_generated' => $sitemapInfo['sitemap.xml']['last_modified'] ?? null
                ],
                'robots' => [
                    'info' => $robotsInfo,
                    'is_valid' => $this->robotsGenerator->isValid(),
                    'url' => $this->robotsGenerator->getRobotsUrl()
                ],
                'environment' => app()->environment(),
                'base_url' => config('app.url')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get SEO status', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'error' => 'Failed to get SEO status'
            ], 500);
        }
    }
    
    /**
     * Clear SEO cache
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            $this->sitemapGenerator->clearCache();
            Cache::forget('robots_content');
            Cache::forget('robots_last_modified');
            
            return response()->json([
                'success' => true,
                'message' => 'SEO cache berhasil dibersihkan'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to clear SEO cache', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache'
            ], 500);
        }
    }
    
    /**
     * Ping search engines about sitemap update
     */
    public function pingSitemapUpdate(Request $request): JsonResponse
    {
        try {
            $sitemapUrl = urlencode(config('app.url') . '/sitemap.xml');
            $results = [];
            
            // Ping Google
            $googleUrl = "https://www.google.com/ping?sitemap={$sitemapUrl}";
            $googleResponse = @file_get_contents($googleUrl, false, stream_context_create([
                'http' => ['timeout' => 10]
            ]));
            $results['google'] = $googleResponse !== false;
            
            // Ping Bing
            $bingUrl = "https://www.bing.com/ping?sitemap={$sitemapUrl}";
            $bingResponse = @file_get_contents($bingUrl, false, stream_context_create([
                'http' => ['timeout' => 10]
            ]));
            $results['bing'] = $bingResponse !== false;
            
            Log::info('Sitemap ping results', $results);
            
            return response()->json([
                'success' => true,
                'message' => 'Search engines notified about sitemap update',
                'results' => $results
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to ping search engines', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to notify search engines'
            ], 500);
        }
    }
    
    /**
     * Serve sitemap file with caching
     */
    private function serveSitemapFile(string $filename, callable $generator): Response
    {
        try {
            $staticPath = public_path($filename);
            
            // Check if file exists and is recent (less than 6 hours old)
            if (file_exists($staticPath) && (time() - filemtime($staticPath)) < 21600) {
                return $this->serveStaticFile($staticPath, 'application/xml');
            }
            
            // Generate fresh file
            $success = $generator();
            
            if ($success && file_exists($staticPath)) {
                return $this->serveStaticFile($staticPath, 'application/xml');
            }
            
            // Return 404 if generation failed
            return response('Sitemap not found', 404)
                ->header('Content-Type', 'text/plain');
            
        } catch (\Exception $e) {
            Log::error("Failed to serve {$filename}", [
                'error' => $e->getMessage()
            ]);
            
            return response('Sitemap temporarily unavailable', 503)
                ->header('Content-Type', 'text/plain');
        }
    }
    
    /**
     * Serve static file with proper headers
     */
    private function serveStaticFile(string $path, string $contentType): Response
    {
        $content = file_get_contents($path);
        $lastModified = filemtime($path);
        
        return response($content)
            ->header('Content-Type', $contentType . '; charset=utf-8')
            ->header('Last-Modified', gmdate('D, d M Y H:i:s', $lastModified) . ' GMT')
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('X-Robots-Tag', 'noindex');
    }
}