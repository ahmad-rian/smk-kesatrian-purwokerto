<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk generate favicon otomatis dari logo situs
 * 
 * Fitur:
 * - Generate multiple sizes favicon (16x16, 32x32, 48x48, 64x64, 128x128, 256x256)
 * - Generate Apple Touch Icons (57x57, 60x60, 72x72, 76x76, 114x114, 120x120, 144x144, 152x152, 180x180)
 * - Generate Android Chrome Icons (36x36, 48x48, 72x72, 96x96, 144x144, 192x192, 512x512)
 * - Generate Windows Tile Icons (70x70, 150x150, 310x310)
 * - Generate ICO file dengan multiple sizes
 * - Generate manifest.json untuk PWA
 * - Generate browserconfig.xml untuk Windows
 */
class FaviconGeneratorService
{
    private string $publicPath;
    private string $faviconPath;
    
    public function __construct()
    {
        $this->publicPath = public_path();
        $this->faviconPath = $this->publicPath . '/favicons';
        
        // Buat direktori favicon jika belum ada
        if (!File::exists($this->faviconPath)) {
            File::makeDirectory($this->faviconPath, 0755, true);
        }
    }
    
    /**
     * Generate semua favicon dari logo situs
     */
    public function generateAllFavicons(string $logoPath): array
    {
        try {
            // Validasi file logo
            if (!File::exists($logoPath)) {
                throw new \Exception("Logo file tidak ditemukan: {$logoPath}");
            }
            
            $results = [];
            
            // Copy logo untuk berbagai ukuran favicon
            $results['standard'] = $this->generateStandardFavicons($logoPath);
            $results['apple'] = $this->generateAppleTouchIcons($logoPath);
            $results['android'] = $this->generateAndroidChromeIcons($logoPath);
            $results['windows'] = $this->generateWindowsTileIcons($logoPath);
            $results['ico'] = $this->generateIcoFile($logoPath);
            
            // Generate manifest.json
            $results['manifest'] = $this->generateManifest();
            
            // Generate browserconfig.xml
            $results['browserconfig'] = $this->generateBrowserConfig();
            
            Log::info('Favicon generation completed successfully', $results);
            
            return [
                'success' => true,
                'message' => 'Semua favicon berhasil di-generate',
                'results' => $results,
                'total_files' => $this->countGeneratedFiles($results)
            ];
            
        } catch (\Exception $e) {
            Log::error('Favicon generation failed', [
                'error' => $e->getMessage(),
                'logo_path' => $logoPath
            ]);
            
            return [
                'success' => false,
                'message' => 'Gagal generate favicon: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get site settings dari database atau default values
     */
    private function getSiteSettings(): array
    {
        // Default settings jika SiteSettingsService belum tersedia
        return [
            'site_name' => 'SMK Kesatrian Purwokerto',
            'site_short_name' => 'SMK Kesatrian',
            'site_description' => 'Sekolah Menengah Kejuruan Terbaik di Purwokerto',
            'theme_color' => '#1f2937',
            'background_color' => '#ffffff'
        ];
    }
    
    /**
     * Generate standard favicons (16x16, 32x32, 48x48, 64x64, 128x128, 256x256)
     */
    private function generateStandardFavicons(string $logoPath): array
    {
        $sizes = [16, 32, 48, 64, 128, 256];
        $generated = [];
        
        foreach ($sizes as $size) {
            $filename = "favicon-{$size}x{$size}.png";
            $filepath = $this->faviconPath . '/' . $filename;
            
            // Copy logo sebagai favicon (untuk sementara)
            File::copy($logoPath, $filepath);
            
            $generated[] = [
                'size' => "{$size}x{$size}",
                'filename' => $filename,
                'path' => $filepath,
                'url' => '/favicons/' . $filename
            ];
        }
        
        return $generated;
    }
    
    /**
     * Generate Apple Touch Icons
     */
    private function generateAppleTouchIcons(string $logoPath): array
    {
        $sizes = [57, 60, 72, 76, 114, 120, 144, 152, 180];
        $generated = [];
        
        foreach ($sizes as $size) {
            $filename = "apple-touch-icon-{$size}x{$size}.png";
            $filepath = $this->faviconPath . '/' . $filename;
            
            // Copy logo sebagai apple touch icon
            File::copy($logoPath, $filepath);
            
            $generated[] = [
                'size' => "{$size}x{$size}",
                'filename' => $filename,
                'path' => $filepath,
                'url' => '/favicons/' . $filename
            ];
        }
        
        // Generate default apple-touch-icon.png (180x180)
        $defaultFilename = 'apple-touch-icon.png';
        $defaultFilepath = $this->faviconPath . '/' . $defaultFilename;
        
        File::copy($logoPath, $defaultFilepath);
        
        $generated[] = [
            'size' => '180x180',
            'filename' => $defaultFilename,
            'path' => $defaultFilepath,
            'url' => '/favicons/' . $defaultFilename,
            'default' => true
        ];
        
        return $generated;
    }
    
    /**
     * Generate Android Chrome Icons
     */
    private function generateAndroidChromeIcons(string $logoPath): array
    {
        $sizes = [36, 48, 72, 96, 144, 192, 512];
        $generated = [];
        
        foreach ($sizes as $size) {
            $filename = "android-chrome-{$size}x{$size}.png";
            $filepath = $this->faviconPath . '/' . $filename;
            
            // Copy logo sebagai android chrome icon
            File::copy($logoPath, $filepath);
            
            $generated[] = [
                'size' => "{$size}x{$size}",
                'filename' => $filename,
                'path' => $filepath,
                'url' => '/favicons/' . $filename
            ];
        }
        
        return $generated;
    }
    
    /**
     * Generate Windows Tile Icons
     */
    private function generateWindowsTileIcons(string $logoPath): array
    {
        $sizes = [
            ['size' => 70, 'name' => 'small'],
            ['size' => 150, 'name' => 'medium'],
            ['size' => 310, 'name' => 'large']
        ];
        $generated = [];
        
        foreach ($sizes as $tile) {
            $size = $tile['size'];
            $name = $tile['name'];
            $filename = "mstile-{$size}x{$size}.png";
            $filepath = $this->faviconPath . '/' . $filename;
            
            // Copy logo sebagai windows tile
            File::copy($logoPath, $filepath);
            
            $generated[] = [
                'size' => "{$size}x{$size}",
                'filename' => $filename,
                'path' => $filepath,
                'url' => '/favicons/' . $filename,
                'tile_name' => $name
            ];
        }
        
        return $generated;
    }
    
    /**
     * Generate ICO file dengan multiple sizes
     */
    private function generateIcoFile(string $logoPath): array
    {
        $filename = 'favicon.ico';
        $filepath = $this->publicPath . '/' . $filename;
        
        // Copy logo sebagai favicon.ico
        File::copy($logoPath, $filepath);
        
        return [
            'filename' => $filename,
            'path' => $filepath,
            'url' => '/' . $filename,
            'size' => '32x32'
        ];
    }
    
    /**
     * Generate manifest.json untuk PWA
     */
    private function generateManifest(): array
    {
        // Get site settings dari model atau default values
        $siteSettings = $this->getSiteSettings();
        
        $manifest = [
            'name' => $siteSettings['site_name'] ?? 'SMK Kesatrian Purwokerto',
            'short_name' => $siteSettings['site_short_name'] ?? 'SMK Kesatrian',
            'description' => $siteSettings['site_description'] ?? 'Sekolah Menengah Kejuruan Terbaik di Purwokerto',
            'start_url' => '/',
            'display' => 'standalone',
            'theme_color' => $siteSettings['theme_color'] ?? '#1f2937',
            'background_color' => $siteSettings['background_color'] ?? '#ffffff',
            'orientation' => 'portrait-primary',
            'icons' => [
                [
                    'src' => '/favicons/android-chrome-36x36.png',
                    'sizes' => '36x36',
                    'type' => 'image/png',
                    'density' => '0.75'
                ],
                [
                    'src' => '/favicons/android-chrome-48x48.png',
                    'sizes' => '48x48',
                    'type' => 'image/png',
                    'density' => '1.0'
                ],
                [
                    'src' => '/favicons/android-chrome-72x72.png',
                    'sizes' => '72x72',
                    'type' => 'image/png',
                    'density' => '1.5'
                ],
                [
                    'src' => '/favicons/android-chrome-96x96.png',
                    'sizes' => '96x96',
                    'type' => 'image/png',
                    'density' => '2.0'
                ],
                [
                    'src' => '/favicons/android-chrome-144x144.png',
                    'sizes' => '144x144',
                    'type' => 'image/png',
                    'density' => '3.0'
                ],
                [
                    'src' => '/favicons/android-chrome-192x192.png',
                    'sizes' => '192x192',
                    'type' => 'image/png',
                    'density' => '4.0'
                ],
                [
                    'src' => '/favicons/android-chrome-512x512.png',
                    'sizes' => '512x512',
                    'type' => 'image/png'
                ]
            ]
        ];
        
        $manifestPath = $this->publicPath . '/manifest.json';
        File::put($manifestPath, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        return [
            'filename' => 'manifest.json',
            'path' => $manifestPath,
            'url' => '/manifest.json',
            'content' => $manifest
        ];
    }
    
    /**
     * Generate browserconfig.xml untuk Windows
     */
    private function generateBrowserConfig(): array
    {
        // Get site settings dari model atau default values
        $siteSettings = $this->getSiteSettings();
        $tileColor = $siteSettings['theme_color'] ?? '#1f2937';
        
        $browserconfig = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $browserconfig .= '<browserconfig>' . "\n";
        $browserconfig .= '    <msapplication>' . "\n";
        $browserconfig .= '        <tile>' . "\n";
        $browserconfig .= '            <square70x70logo src="/favicons/mstile-70x70.png"/>' . "\n";
        $browserconfig .= '            <square150x150logo src="/favicons/mstile-150x150.png"/>' . "\n";
        $browserconfig .= '            <square310x310logo src="/favicons/mstile-310x310.png"/>' . "\n";
        $browserconfig .= '            <TileColor>' . $tileColor . '</TileColor>' . "\n";
        $browserconfig .= '        </tile>' . "\n";
        $browserconfig .= '    </msapplication>' . "\n";
        $browserconfig .= '</browserconfig>' . "\n";
        
        $browserconfigPath = $this->publicPath . '/browserconfig.xml';
        File::put($browserconfigPath, $browserconfig);
        
        return [
            'filename' => 'browserconfig.xml',
            'path' => $browserconfigPath,
            'url' => '/browserconfig.xml',
            'tile_color' => $tileColor
        ];
    }
    
    /**
     * Hitung total file yang di-generate
     */
    private function countGeneratedFiles(array $results): int
    {
        $count = 0;
        
        foreach ($results as $category => $items) {
            if ($category === 'manifest' || $category === 'browserconfig' || $category === 'ico') {
                $count += 1;
            } else {
                $count += count($items);
            }
        }
        
        return $count;
    }
    
    /**
     * Get favicon HTML tags untuk di-include di head
     */
    public function getFaviconHtmlTags(): string
    {
        $html = [];
        
        // Standard favicon
        $html[] = '<link rel="icon" type="image/x-icon" href="/favicon.ico">';
        $html[] = '<link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">';
        $html[] = '<link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">';
        $html[] = '<link rel="icon" type="image/png" sizes="48x48" href="/favicons/favicon-48x48.png">';
        $html[] = '<link rel="icon" type="image/png" sizes="64x64" href="/favicons/favicon-64x64.png">';
        
        // Apple Touch Icons
        $html[] = '<link rel="apple-touch-icon" href="/favicons/apple-touch-icon.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-touch-icon-57x57.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-touch-icon-60x60.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-touch-icon-72x72.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-touch-icon-76x76.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-touch-icon-114x114.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-touch-icon-120x120.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-touch-icon-144x144.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="152x152" href="/favicons/apple-touch-icon-152x152.png">';
        $html[] = '<link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon-180x180.png">';
        
        // Android Chrome
        $html[] = '<link rel="icon" type="image/png" sizes="192x192" href="/favicons/android-chrome-192x192.png">';
        $html[] = '<link rel="icon" type="image/png" sizes="512x512" href="/favicons/android-chrome-512x512.png">';
        
        // Windows Tiles
        $html[] = '<meta name="msapplication-TileImage" content="/favicons/mstile-150x150.png">';
        $html[] = '<meta name="msapplication-config" content="/browserconfig.xml">';
        
        // PWA Manifest
        $html[] = '<link rel="manifest" href="/manifest.json">';
        
        return implode("\n    ", $html);
    }
    
    /**
     * Hapus semua favicon yang sudah di-generate
     */
    public function cleanupFavicons(): array
    {
        try {
            $deletedFiles = [];
            
            // Hapus direktori favicons
            if (File::exists($this->faviconPath)) {
                $files = File::allFiles($this->faviconPath);
                foreach ($files as $file) {
                    $deletedFiles[] = $file->getFilename();
                }
                File::deleteDirectory($this->faviconPath);
            }
            
            // Hapus file di root public
            $rootFiles = ['favicon.ico', 'manifest.json', 'browserconfig.xml'];
            foreach ($rootFiles as $file) {
                $filepath = $this->publicPath . '/' . $file;
                if (File::exists($filepath)) {
                    File::delete($filepath);
                    $deletedFiles[] = $file;
                }
            }
            
            return [
                'success' => true,
                'message' => 'Semua favicon berhasil dihapus',
                'deleted_files' => $deletedFiles,
                'total_deleted' => count($deletedFiles)
            ];
            
        } catch (\Exception $e) {
            Log::error('Favicon cleanup failed', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Gagal menghapus favicon: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Check apakah favicon sudah di-generate
     */
    public function checkFaviconStatus(): array
    {
        $requiredFiles = [
            'favicon.ico',
            'manifest.json',
            'browserconfig.xml',
            'favicons/favicon-16x16.png',
            'favicons/favicon-32x32.png',
            'favicons/apple-touch-icon.png',
            'favicons/android-chrome-192x192.png',
            'favicons/android-chrome-512x512.png'
        ];
        
        $existingFiles = [];
        $missingFiles = [];
        
        foreach ($requiredFiles as $file) {
            $filepath = $this->publicPath . '/' . $file;
            if (File::exists($filepath)) {
                $existingFiles[] = [
                    'file' => $file,
                    'size' => File::size($filepath),
                    'modified' => File::lastModified($filepath)
                ];
            } else {
                $missingFiles[] = $file;
            }
        }
        
        $isComplete = empty($missingFiles);
        
        return [
            'is_complete' => $isComplete,
            'total_required' => count($requiredFiles),
            'total_existing' => count($existingFiles),
            'total_missing' => count($missingFiles),
            'existing_files' => $existingFiles,
            'missing_files' => $missingFiles,
            'status' => $isComplete ? 'complete' : 'incomplete',
            'message' => $isComplete 
                ? 'Semua favicon sudah tersedia' 
                : count($missingFiles) . ' favicon belum di-generate'
        ];
    }
}