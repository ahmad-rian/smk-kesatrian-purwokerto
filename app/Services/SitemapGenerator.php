<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

/**
 * Sitemap Generator Service
 * 
 * Fitur:
 * - Generate sitemap.xml dinamis dari database
 * - Support untuk multiple sitemap files
 * - Automatic priority dan changefreq calculation
 * - Image sitemap untuk galeri
 * - News sitemap untuk berita terbaru
 * - Cache untuk performa optimal
 * - Gzip compression support
 */
class SitemapGenerator
{
    private string $sitemapPath;
    private string $baseUrl;
    private int $maxUrlsPerSitemap = 50000;
    
    public function __construct()
    {
        $this->sitemapPath = public_path('sitemap.xml');
        $this->baseUrl = rtrim(config('app.url'), '/');
    }
    
    /**
     * Generate main sitemap index
     */
    public function generateIndex(): bool
    {
        try {
            $sitemaps = $this->getSitemapList();
            $content = $this->generateIndexContent($sitemaps);
            
            File::put($this->sitemapPath, $content);
            
            // Generate individual sitemaps
            $this->generatePagesSitemap();
            $this->generateNewsSitemap();
            $this->generateActivitiesSitemap();
            $this->generateGalleriesSitemap();
            
            // Clear cache
            Cache::forget('sitemap_index');
            Cache::forget('sitemap_urls_count');
            
            Log::info('Sitemap index generated successfully', [
                'path' => $this->sitemapPath,
                'sitemaps_count' => count($sitemaps)
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate sitemap index', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    

    
    /**
     * Generate sitemap index content
     */
    private function generateIndexContent(array $sitemaps): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($sitemaps as $sitemap) {
            $xml .= '  <sitemap>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($sitemap['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $sitemap['lastmod'] . '</lastmod>' . "\n";
            $xml .= '  </sitemap>' . "\n";
        }
        
        $xml .= '</sitemapindex>';
        
        return $xml;
    }
    
    /**
     * Get list of sitemaps
     */
    private function getSitemapList(): array
    {
        return [
            [
                'loc' => $this->baseUrl . '/sitemap-pages.xml',
                'lastmod' => now()->toISOString()
            ],
            [
                'loc' => $this->baseUrl . '/sitemap-news.xml',
                'lastmod' => $this->getLastModified('news')
            ],
            [
                'loc' => $this->baseUrl . '/sitemap-activities.xml',
                'lastmod' => $this->getLastModified('school_activities')
            ],
            [
                'loc' => $this->baseUrl . '/sitemap-galleries.xml',
                'lastmod' => $this->getLastModified('galleries')
            ]
        ];
    }
    
    /**
     * Generate pages sitemap (static pages)
     */
    public function generatePagesSitemap(): bool
    {
        try {
            $urls = $this->getStaticPages();
            $content = $this->generateUrlsetContent($urls);
            
            File::put(public_path('sitemap-pages.xml'), $content);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate pages sitemap', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
    
    /**
     * Generate news sitemap
     */
    public function generateNewsSitemap(): bool
    {
        try {
            $urls = $this->getNewsUrls();
            $content = $this->generateUrlsetContent($urls);
            
            File::put(public_path('sitemap-news.xml'), $content);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate news sitemap', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
    
    /**
     * Generate activities sitemap
     */
    public function generateActivitiesSitemap(): bool
    {
        try {
            $urls = $this->getActivitiesUrls();
            $content = $this->generateUrlsetContent($urls);
            
            File::put(public_path('sitemap-activities.xml'), $content);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate activities sitemap', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
    
    /**
     * Generate galleries sitemap with images
     */
    public function generateGalleriesSitemap(): bool
    {
        try {
            $urls = $this->getGalleriesUrls();
            $content = $this->generateImageSitemapContent($urls);
            
            File::put(public_path('sitemap-galleries.xml'), $content);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate galleries sitemap', [
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }
    
    /**
     * Get static pages URLs
     */
    private function getStaticPages(): array
    {
        return [
            [
                'loc' => $this->baseUrl . '/',
                'lastmod' => now()->toISOString(),
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => $this->baseUrl . '/profil',
                'lastmod' => now()->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'loc' => $this->baseUrl . '/jurusan',
                'lastmod' => now()->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.8'
            ],
            [
                'loc' => $this->baseUrl . '/fasilitas',
                'lastmod' => now()->toISOString(),
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ],
            [
                'loc' => $this->baseUrl . '/berita',
                'lastmod' => $this->getLastModified('news'),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'loc' => $this->baseUrl . '/kegiatan',
                'lastmod' => $this->getLastModified('school_activities'),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => $this->baseUrl . '/galeri',
                'lastmod' => $this->getLastModified('galleries'),
                'changefreq' => 'weekly',
                'priority' => '0.7'
            ],
            [
                'loc' => $this->baseUrl . '/kontak',
                'lastmod' => now()->toISOString(),
                'changefreq' => 'yearly',
                'priority' => '0.6'
            ]
        ];
    }
    
    /**
     * Get news URLs from database
     */
    private function getNewsUrls(): array
    {
        $urls = [];
        
        try {
            $news = DB::table('news')
                ->where('is_published', true)
                ->select('id', 'slug', 'updated_at', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($news as $item) {
                $urls[] = [
                    'loc' => $this->baseUrl . '/berita/' . $item->slug,
                    'lastmod' => Carbon::parse($item->updated_at)->toISOString(),
                    'changefreq' => $this->calculateChangeFreq($item->updated_at, $item->created_at),
                    'priority' => $this->calculatePriority($item->created_at, 'news')
                ];
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to get news URLs for sitemap', [
                'error' => $e->getMessage()
            ]);
        }
        
        return $urls;
    }
    
    /**
     * Get activities URLs from database
     */
    private function getActivitiesUrls(): array
    {
        $urls = [];
        
        try {
            $activities = DB::table('school_activities')
                ->where('is_published', true)
                ->select('id', 'slug', 'updated_at', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($activities as $item) {
                $urls[] = [
                    'loc' => $this->baseUrl . '/kegiatan/' . $item->slug,
                    'lastmod' => Carbon::parse($item->updated_at)->toISOString(),
                    'changefreq' => $this->calculateChangeFreq($item->updated_at, $item->created_at),
                    'priority' => $this->calculatePriority($item->created_at, 'activity')
                ];
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to get activities URLs for sitemap', [
                'error' => $e->getMessage()
            ]);
        }
        
        return $urls;
    }
    
    /**
     * Get galleries URLs with images
     */
    private function getGalleriesUrls(): array
    {
        $urls = [];
        
        try {
            $galleries = DB::table('galleries')
                ->where('is_published', true)
                ->select('id', 'slug', 'title', 'description', 'updated_at', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
            
            foreach ($galleries as $gallery) {
                // Get gallery images
                $images = DB::table('gallery_images')
                    ->where('gallery_id', $gallery->id)
                    ->select('image_path', 'alt_text', 'title')
                    ->get();
                
                $galleryImages = [];
                foreach ($images as $image) {
                    $galleryImages[] = [
                        'loc' => $this->baseUrl . '/storage/' . $image->image_path,
                        'caption' => $image->alt_text ?: $image->title,
                        'title' => $image->title
                    ];
                }
                
                $urls[] = [
                    'loc' => $this->baseUrl . '/galeri/' . $gallery->slug,
                    'lastmod' => Carbon::parse($gallery->updated_at)->toISOString(),
                    'changefreq' => $this->calculateChangeFreq($gallery->updated_at, $gallery->created_at),
                    'priority' => $this->calculatePriority($gallery->created_at, 'gallery'),
                    'images' => $galleryImages
                ];
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to get galleries URLs for sitemap', [
                'error' => $e->getMessage()
            ]);
        }
        
        return $urls;
    }
    
    /**
     * Generate urlset content
     */
    private function generateUrlsetContent(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Generate image sitemap content
     */
    private function generateImageSitemapContent(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
            $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
            
            // Add images
            if (!empty($url['images'])) {
                foreach ($url['images'] as $image) {
                    $xml .= '    <image:image>' . "\n";
                    $xml .= '      <image:loc>' . htmlspecialchars($image['loc']) . '</image:loc>' . "\n";
                    if (!empty($image['caption'])) {
                        $xml .= '      <image:caption>' . htmlspecialchars($image['caption']) . '</image:caption>' . "\n";
                    }
                    if (!empty($image['title'])) {
                        $xml .= '      <image:title>' . htmlspecialchars($image['title']) . '</image:title>' . "\n";
                    }
                    $xml .= '    </image:image>' . "\n";
                }
            }
            
            $xml .= '  </url>' . "\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * Calculate change frequency based on update pattern
     */
    private function calculateChangeFreq(string $updatedAt, string $createdAt): string
    {
        $updated = Carbon::parse($updatedAt);
        $created = Carbon::parse($createdAt);
        $daysSinceCreated = $created->diffInDays(now());
        $daysSinceUpdated = $updated->diffInDays(now());
        
        // If updated recently (within 7 days)
        if ($daysSinceUpdated <= 7) {
            return 'weekly';
        }
        
        // If created recently (within 30 days)
        if ($daysSinceCreated <= 30) {
            return 'weekly';
        }
        
        // If updated within last 3 months
        if ($daysSinceUpdated <= 90) {
            return 'monthly';
        }
        
        // Old content
        return 'yearly';
    }
    
    /**
     * Calculate priority based on content type and age
     */
    private function calculatePriority(string $createdAt, string $type): string
    {
        $created = Carbon::parse($createdAt);
        $daysSinceCreated = $created->diffInDays(now());
        
        $basePriority = match ($type) {
            'news' => 0.8,
            'activity' => 0.7,
            'gallery' => 0.6,
            default => 0.5
        };
        
        // Reduce priority for older content
        if ($daysSinceCreated > 365) {
            $basePriority -= 0.2;
        } elseif ($daysSinceCreated > 90) {
            $basePriority -= 0.1;
        }
        
        // Ensure priority is between 0.1 and 1.0
        $priority = max(0.1, min(1.0, $basePriority));
        
        return number_format($priority, 1);
    }
    
    /**
     * Get last modified date for a table
     */
    private function getLastModified(string $table): string
    {
        try {
            $lastModified = DB::table($table)
                ->max('updated_at');
            
            return $lastModified ? Carbon::parse($lastModified)->toISOString() : now()->toISOString();
            
        } catch (\Exception $e) {
            return now()->toISOString();
        }
    }
    
    /**
     * Get sitemap file info
     */
    public function getFileInfo(): array
    {
        $files = [
            'sitemap.xml',
            'sitemap-pages.xml',
            'sitemap-news.xml',
            'sitemap-activities.xml',
            'sitemap-galleries.xml'
        ];
        
        $info = [];
        
        foreach ($files as $file) {
            $path = public_path($file);
            
            $info[$file] = [
                'exists' => File::exists($path),
                'size' => File::exists($path) ? File::size($path) : 0,
                'last_modified' => File::exists($path) ? File::lastModified($path) : null,
                'url' => $this->baseUrl . '/' . $file
            ];
        }
        
        return $info;
    }
    
    /**
     * Get total URLs count across all sitemaps
     */
    public function getTotalUrlsCount(): int
    {
        return Cache::remember('sitemap_urls_count', 3600, function () {
            $count = 0;
            
            // Static pages
            $count += count($this->getStaticPages());
            
            // Dynamic content
            try {
                $count += DB::table('news')->where('is_published', true)->count();
                $count += DB::table('school_activities')->where('is_published', true)->count();
                $count += DB::table('galleries')->where('is_published', true)->count();
            } catch (\Exception $e) {
                Log::warning('Failed to count URLs for sitemap', [
                    'error' => $e->getMessage()
                ]);
            }
            
            return $count;
        });
    }
    
    /**
     * Validate sitemap XML
     */
    public function validateXml(string $filePath): array
    {
        if (!File::exists($filePath)) {
            return [
                'valid' => false,
                'errors' => ['File does not exist']
            ];
        }
        
        $errors = [];
        
        // Enable user error handling
        libxml_use_internal_errors(true);
        
        // Load XML
        $xml = simplexml_load_file($filePath);
        
        if ($xml === false) {
            $xmlErrors = libxml_get_errors();
            foreach ($xmlErrors as $error) {
                $errors[] = trim($error->message);
            }
        }
        
        libxml_clear_errors();
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Clear sitemap cache
     */
    public function clearCache(): void
    {
        Cache::forget('sitemap_index');
        Cache::forget('sitemap_urls_count');
        
        // Clear individual sitemap caches if they exist
        $cacheKeys = [
            'sitemap_pages',
            'sitemap_news',
            'sitemap_activities',
            'sitemap_galleries'
        ];
        
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
    
    /**
     * Generate individual sitemap file
     */
    private function generateSitemapFile(string $filename, array $urls): bool
    {
        try {
            $xml = new \DOMDocument('1.0', 'UTF-8');
            $xml->formatOutput = true;
            
            $urlset = $xml->createElement('urlset');
            $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $urlset->setAttribute('xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
            $urlset->setAttribute('xmlns:news', 'http://www.google.com/schemas/sitemap-news/0.9');
            $xml->appendChild($urlset);
            
            foreach ($urls as $urlData) {
                $url = $xml->createElement('url');
                
                $loc = $xml->createElement('loc', htmlspecialchars($urlData['loc']));
                $url->appendChild($loc);
                
                if (isset($urlData['lastmod'])) {
                    $lastmod = $xml->createElement('lastmod', $urlData['lastmod']);
                    $url->appendChild($lastmod);
                }
                
                if (isset($urlData['changefreq'])) {
                    $changefreq = $xml->createElement('changefreq', $urlData['changefreq']);
                    $url->appendChild($changefreq);
                }
                
                if (isset($urlData['priority'])) {
                    $priority = $xml->createElement('priority', $urlData['priority']);
                    $url->appendChild($priority);
                }
                
                // Add images if present
                if (isset($urlData['images'])) {
                    foreach ($urlData['images'] as $imageData) {
                        $image = $xml->createElement('image:image');
                        $imageLoc = $xml->createElement('image:loc', htmlspecialchars($imageData['loc']));
                        $image->appendChild($imageLoc);
                        
                        if (isset($imageData['title'])) {
                            $imageTitle = $xml->createElement('image:title', htmlspecialchars($imageData['title']));
                            $image->appendChild($imageTitle);
                        }
                        
                        if (isset($imageData['caption'])) {
                            $imageCaption = $xml->createElement('image:caption', htmlspecialchars($imageData['caption']));
                            $image->appendChild($imageCaption);
                        }
                        
                        $url->appendChild($image);
                    }
                }
                
                $urlset->appendChild($url);
            }
            
            $filePath = public_path($filename);
            return $xml->save($filePath) !== false;
            
        } catch (\Exception $e) {
            Log::error("Failed to generate {$filename}", [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}