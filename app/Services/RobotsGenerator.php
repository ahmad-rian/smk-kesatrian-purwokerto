<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/**
 * Robots.txt Generator Service
 * 
 * Fitur:
 * - Generate robots.txt otomatis berdasarkan environment
 * - Konfigurasi crawl delay dan user agents
 * - Sitemap reference otomatis
 * - Block admin dan sensitive paths
 * - Support untuk multiple environments
 * - Cache untuk performa optimal
 */
class RobotsGenerator
{
    private string $robotsPath;
    private array $config;
    
    public function __construct()
    {
        $this->robotsPath = public_path('robots.txt');
        $this->config = config('seo.robots', []);
    }
    
    /**
     * Generate robots.txt file
     */
    public function generate(): bool
    {
        try {
            $content = $this->generateContent();
            
            // Backup existing robots.txt if exists
            if (File::exists($this->robotsPath)) {
                $backupPath = $this->robotsPath . '.backup.' . date('Y-m-d-H-i-s');
                File::copy($this->robotsPath, $backupPath);
                
                // Keep only last 5 backups
                $this->cleanupBackups();
            }
            
            // Write new robots.txt
            File::put($this->robotsPath, $content);
            
            // Clear cache
            Cache::forget('robots_content');
            Cache::forget('robots_last_modified');
            
            Log::info('Robots.txt generated successfully', [
                'path' => $this->robotsPath,
                'size' => strlen($content)
            ]);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error('Failed to generate robots.txt', [
                'error' => $e->getMessage(),
                'path' => $this->robotsPath
            ]);
            
            return false;
        }
    }
    
    /**
     * Generate robots.txt content
     */
    private function generateContent(): string
    {
        $content = [];
        
        // Header comment
        $content[] = '# Robots.txt for ' . config('app.name');
        $content[] = '# Generated on: ' . now()->format('Y-m-d H:i:s T');
        $content[] = '# Environment: ' . app()->environment();
        $content[] = '';
        
        // Environment-specific rules
        if (app()->environment('production')) {
            $content = array_merge($content, $this->getProductionRules());
        } else {
            $content = array_merge($content, $this->getDevelopmentRules());
        }
        
        // Common rules
        $content = array_merge($content, $this->getCommonRules());
        
        // Sitemap reference
        $content = array_merge($content, $this->getSitemapReferences());
        
        return implode("\n", $content);
    }
    
    /**
     * Get production environment rules
     */
    private function getProductionRules(): array
    {
        return [
            '# Production Rules - Allow most crawling',
            'User-agent: *',
            'Allow: /',
            '',
            '# Block admin and sensitive areas',
            'Disallow: /admin/',
            'Disallow: /login',
            'Disallow: /register',
            'Disallow: /password/',
            'Disallow: /api/',
            'Disallow: /storage/app/',
            'Disallow: /storage/framework/',
            'Disallow: /vendor/',
            'Disallow: /.env',
            'Disallow: /config/',
            'Disallow: /database/',
            'Disallow: /tests/',
            '',
            '# Block search and filter pages to prevent duplicate content',
            'Disallow: /*?search=',
            'Disallow: /*?filter=',
            'Disallow: /*?sort=',
            'Disallow: /*?page=',
            'Disallow: /*?utm_',
            '',
            '# Allow important static assets',
            'Allow: /css/',
            'Allow: /js/',
            'Allow: /images/',
            'Allow: /storage/images/',
            'Allow: /storage/galleries/',
            'Allow: /favicon.ico',
            'Allow: /robots.txt',
            'Allow: /sitemap.xml',
            '',
            '# Crawl delay for respectful crawling',
            'Crawl-delay: 1',
            ''
        ];
    }
    
    /**
     * Get development environment rules
     */
    private function getDevelopmentRules(): array
    {
        return [
            '# Development Rules - Block all crawling',
            'User-agent: *',
            'Disallow: /',
            '',
            '# This is a development/staging site',
            '# Please do not index this content',
            ''
        ];
    }
    
    /**
     * Get common rules for all environments
     */
    private function getCommonRules(): array
    {
        $rules = [];
        
        // Specific bot rules
        if (app()->environment('production')) {
            $rules = array_merge($rules, [
                '# Specific bot configurations',
                'User-agent: Googlebot',
                'Allow: /',
                'Crawl-delay: 1',
                '',
                'User-agent: Bingbot',
                'Allow: /',
                'Crawl-delay: 2',
                '',
                'User-agent: Slurp',
                'Allow: /',
                'Crawl-delay: 2',
                '',
                '# Block aggressive crawlers',
                'User-agent: AhrefsBot',
                'Disallow: /',
                '',
                'User-agent: MJ12bot',
                'Disallow: /',
                '',
                'User-agent: DotBot',
                'Disallow: /',
                ''
            ]);
        }
        
        return $rules;
    }
    
    /**
     * Get sitemap references
     */
    private function getSitemapReferences(): array
    {
        $sitemaps = [];
        
        if (app()->environment('production')) {
            $baseUrl = rtrim(config('app.url'), '/');
            
            $sitemaps[] = '';
            $sitemaps[] = '# Sitemaps';
            $sitemaps[] = 'Sitemap: ' . $baseUrl . '/sitemap.xml';
            
            // Additional sitemaps if they exist
            $additionalSitemaps = [
                '/sitemap-pages.xml',
                '/sitemap-news.xml',
                '/sitemap-activities.xml',
                '/sitemap-galleries.xml'
            ];
            
            foreach ($additionalSitemaps as $sitemap) {
                $sitemaps[] = 'Sitemap: ' . $baseUrl . $sitemap;
            }
        }
        
        return $sitemaps;
    }
    
    /**
     * Get current robots.txt content
     */
    public function getCurrentContent(): ?string
    {
        if (!File::exists($this->robotsPath)) {
            return null;
        }
        
        return Cache::remember('robots_content', 3600, function () {
            return File::get($this->robotsPath);
        });
    }
    
    /**
     * Check if robots.txt exists and is valid
     */
    public function isValid(): bool
    {
        if (!File::exists($this->robotsPath)) {
            return false;
        }
        
        $content = $this->getCurrentContent();
        
        // Basic validation
        return !empty($content) && 
               str_contains($content, 'User-agent:') &&
               (str_contains($content, 'Allow:') || str_contains($content, 'Disallow:'));
    }
    
    /**
     * Get robots.txt file info
     */
    public function getFileInfo(): array
    {
        if (!File::exists($this->robotsPath)) {
            return [
                'exists' => false,
                'size' => 0,
                'last_modified' => null,
                'is_writable' => is_writable(dirname($this->robotsPath))
            ];
        }
        
        return [
            'exists' => true,
            'size' => File::size($this->robotsPath),
            'last_modified' => File::lastModified($this->robotsPath),
            'is_writable' => File::isWritable($this->robotsPath),
            'path' => $this->robotsPath
        ];
    }
    
    /**
     * Validate robots.txt syntax
     */
    public function validateSyntax(string $content = null): array
    {
        $content = $content ?? $this->getCurrentContent();
        $errors = [];
        $warnings = [];
        
        if (empty($content)) {
            $errors[] = 'Robots.txt is empty';
            return ['errors' => $errors, 'warnings' => $warnings];
        }
        
        $lines = explode("\n", $content);
        $hasUserAgent = false;
        $hasDirective = false;
        
        foreach ($lines as $lineNum => $line) {
            $line = trim($line);
            
            // Skip empty lines and comments
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }
            
            // Check for User-agent
            if (str_starts_with($line, 'User-agent:')) {
                $hasUserAgent = true;
                $userAgent = trim(substr($line, 11));
                if (empty($userAgent)) {
                    $errors[] = "Line " . ($lineNum + 1) . ": User-agent cannot be empty";
                }
                continue;
            }
            
            // Check for directives
            if (preg_match('/^(Allow|Disallow|Crawl-delay|Sitemap):/i', $line)) {
                $hasDirective = true;
                
                // Validate Sitemap URLs
                if (str_starts_with($line, 'Sitemap:')) {
                    $url = trim(substr($line, 8));
                    if (!filter_var($url, FILTER_VALIDATE_URL)) {
                        $errors[] = "Line " . ($lineNum + 1) . ": Invalid sitemap URL: $url";
                    }
                }
                continue;
            }
            
            // Unknown directive
            $warnings[] = "Line " . ($lineNum + 1) . ": Unknown directive: $line";
        }
        
        if (!$hasUserAgent) {
            $errors[] = 'No User-agent directive found';
        }
        
        if (!$hasDirective) {
            $warnings[] = 'No Allow/Disallow directives found';
        }
        
        return [
            'errors' => $errors,
            'warnings' => $warnings,
            'is_valid' => empty($errors)
        ];
    }
    
    /**
     * Backup current robots.txt
     */
    public function backup(): ?string
    {
        if (!File::exists($this->robotsPath)) {
            return null;
        }
        
        $backupPath = $this->robotsPath . '.backup.' . date('Y-m-d-H-i-s');
        
        if (File::copy($this->robotsPath, $backupPath)) {
            return $backupPath;
        }
        
        return null;
    }
    
    /**
     * Restore from backup
     */
    public function restore(string $backupPath): bool
    {
        if (!File::exists($backupPath)) {
            return false;
        }
        
        return File::copy($backupPath, $this->robotsPath);
    }
    
    /**
     * Clean up old backup files
     */
    private function cleanupBackups(): void
    {
        $backupPattern = $this->robotsPath . '.backup.*';
        $backups = glob($backupPattern);
        
        if (count($backups) > 5) {
            // Sort by modification time (oldest first)
            usort($backups, function ($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Remove oldest backups, keep only 5 most recent
            $toDelete = array_slice($backups, 0, -5);
            foreach ($toDelete as $backup) {
                File::delete($backup);
            }
        }
    }
    
    /**
     * Get robots.txt URL
     */
    public function getRobotsUrl(): string
    {
        return URL::to('/robots.txt');
    }
    
    /**
     * Test robots.txt accessibility
     */
    public function testAccessibility(): array
    {
        $url = $this->getRobotsUrl();
        
        try {
            $response = file_get_contents($url, false, stream_context_create([
                'http' => [
                    'timeout' => 10,
                    'user_agent' => 'SEO-Test-Bot/1.0'
                ]
            ]));
            
            return [
                'accessible' => true,
                'status_code' => 200,
                'content_length' => strlen($response),
                'content_type' => 'text/plain'
            ];
            
        } catch (\Exception $e) {
            return [
                'accessible' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}