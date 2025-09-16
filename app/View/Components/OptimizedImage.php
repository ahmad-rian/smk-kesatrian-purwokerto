<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * Optimized Image Component
 * 
 * Komponen untuk menampilkan gambar yang dioptimasi dengan fitur:
 * - Auto-generate alt text dari filename atau context
 * - Lazy loading dengan intersection observer
 * - Responsive images dengan multiple sizes
 * - WebP format support dengan fallback
 * - SEO-friendly attributes
 * - Loading placeholder
 * 
 * @author SMK Kesatrian
 * @version 1.0
 */
class OptimizedImage extends Component
{
    public string $src;
    public ?string $alt;
    public ?string $title;
    public string $class;
    public string $sizes;
    public bool $lazy;
    public bool $webp;
    public ?string $placeholder;
    public ?int $width;
    public ?int $height;
    public string $loading;
    public string $decoding;
    public ?string $context;
    
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $src,
        ?string $alt = null,
        ?string $title = null,
        string $class = '',
        string $sizes = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw',
        bool $lazy = true,
        bool $webp = true,
        ?string $placeholder = null,
        ?int $width = null,
        ?int $height = null,
        string $loading = 'lazy',
        string $decoding = 'async',
        ?string $context = null
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->title = $title;
        $this->class = $class;
        $this->sizes = $sizes;
        $this->lazy = $lazy;
        $this->webp = $webp;
        $this->placeholder = $placeholder;
        $this->width = $width;
        $this->height = $height;
        $this->loading = $loading;
        $this->decoding = $decoding;
        $this->context = $context;
    }
    
    /**
     * Generate alt text otomatis dari filename atau context
     */
    public function getAltText(): string
    {
        if ($this->alt) {
            return $this->alt;
        }
        
        // Extract filename without extension
        $filename = pathinfo($this->src, PATHINFO_FILENAME);
        
        // Clean filename untuk alt text
        $altText = $this->cleanFilenameForAlt($filename);
        
        // Add context if provided
        if ($this->context) {
            $altText = $this->context . ' - ' . $altText;
        }
        
        return $altText;
    }
    
    /**
     * Clean filename untuk dijadikan alt text yang SEO-friendly
     */
    private function cleanFilenameForAlt(string $filename): string
    {
        // Remove common prefixes/suffixes
        $filename = preg_replace('/^(img|image|photo|pic)[-_]?/i', '', $filename);
        $filename = preg_replace('/[-_]?(img|image|photo|pic)$/i', '', $filename);
        
        // Replace separators with spaces
        $filename = str_replace(['-', '_', '.'], ' ', $filename);
        
        // Remove numbers at the end (like IMG_001)
        $filename = preg_replace('/\s+\d+$/', '', $filename);
        
        // Clean multiple spaces
        $filename = preg_replace('/\s+/', ' ', $filename);
        
        // Capitalize words
        $filename = Str::title(trim($filename));
        
        // If empty, provide default
        if (empty($filename)) {
            $filename = 'Image';
        }
        
        return $filename;
    }
    
    /**
     * Get title attribute
     */
    public function getTitleText(): ?string
    {
        return $this->title ?: $this->getAltText();
    }
    
    /**
     * Generate WebP version URL
     */
    public function getWebpSrc(): ?string
    {
        if (!$this->webp) {
            return null;
        }
        
        $pathInfo = pathinfo($this->src);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Check if WebP version exists
        if ($this->isExternalUrl($this->src)) {
            return null; // Can't generate WebP for external URLs
        }
        
        $publicPath = public_path(ltrim($webpPath, '/'));
        if (file_exists($publicPath)) {
            return $webpPath;
        }
        
        return null;
    }
    
    /**
     * Generate responsive srcset
     */
    public function getSrcset(): string
    {
        if ($this->isExternalUrl($this->src)) {
            return $this->src; // Can't generate responsive images for external URLs
        }
        
        $pathInfo = pathinfo($this->src);
        $basePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'];
        $extension = $pathInfo['extension'];
        
        $sizes = [320, 640, 768, 1024, 1280, 1920];
        $srcset = [];
        
        foreach ($sizes as $size) {
            $responsivePath = $basePath . '-' . $size . 'w.' . $extension;
            $publicPath = public_path(ltrim($responsivePath, '/'));
            
            if (file_exists($publicPath)) {
                $srcset[] = $responsivePath . ' ' . $size . 'w';
            }
        }
        
        // Fallback to original if no responsive images
        if (empty($srcset)) {
            return $this->src;
        }
        
        return implode(', ', $srcset);
    }
    
    /**
     * Generate WebP srcset
     */
    public function getWebpSrcset(): ?string
    {
        if (!$this->webp || $this->isExternalUrl($this->src)) {
            return null;
        }
        
        $pathInfo = pathinfo($this->src);
        $basePath = $pathInfo['dirname'] . '/' . $pathInfo['filename'];
        
        $sizes = [320, 640, 768, 1024, 1280, 1920];
        $srcset = [];
        
        foreach ($sizes as $size) {
            $webpPath = $basePath . '-' . $size . 'w.webp';
            $publicPath = public_path(ltrim($webpPath, '/'));
            
            if (file_exists($publicPath)) {
                $srcset[] = $webpPath . ' ' . $size . 'w';
            }
        }
        
        if (empty($srcset)) {
            // Try single WebP version
            $webpSrc = $this->getWebpSrc();
            return $webpSrc ? $webpSrc : null;
        }
        
        return implode(', ', $srcset);
    }
    
    /**
     * Get placeholder image
     */
    public function getPlaceholder(): string
    {
        if ($this->placeholder) {
            return $this->placeholder;
        }
        
        // Generate simple SVG placeholder
        $width = $this->width ?: 400;
        $height = $this->height ?: 300;
        
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">' .
               '<rect width="100%" height="100%" fill="#f3f4f6"/>' .
               '<text x="50%" y="50%" font-family="Arial, sans-serif" font-size="14" fill="#9ca3af" text-anchor="middle" dy=".3em">Loading...</text>' .
               '</svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
    
    /**
     * Check if URL is external
     */
    private function isExternalUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) && !Str::startsWith($url, [config('app.url'), '/']);
    }
    
    /**
     * Get image dimensions
     */
    public function getDimensions(): array
    {
        if ($this->width && $this->height) {
            return ['width' => $this->width, 'height' => $this->height];
        }
        
        if ($this->isExternalUrl($this->src)) {
            return ['width' => null, 'height' => null];
        }
        
        $publicPath = public_path(ltrim($this->src, '/'));
        
        if (!file_exists($publicPath)) {
            return ['width' => null, 'height' => null];
        }
        
        try {
            $imageSize = getimagesize($publicPath);
            return [
                'width' => $imageSize[0] ?? null,
                'height' => $imageSize[1] ?? null
            ];
        } catch (\Exception $e) {
            return ['width' => null, 'height' => null];
        }
    }
    
    /**
     * Generate structured data untuk gambar
     */
    public function getStructuredData(): array
    {
        $dimensions = $this->getDimensions();
        
        return [
            '@type' => 'ImageObject',
            'url' => url($this->src),
            'name' => $this->getAltText(),
            'description' => $this->getTitleText(),
            'width' => $dimensions['width'],
            'height' => $dimensions['height'],
            'encodingFormat' => $this->getImageFormat()
        ];
    }
    
    /**
     * Get image format/MIME type
     */
    private function getImageFormat(): string
    {
        $extension = strtolower(pathinfo($this->src, PATHINFO_EXTENSION));
        
        $formats = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml'
        ];
        
        return $formats[$extension] ?? 'image/jpeg';
    }
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.optimized-image');
    }
}