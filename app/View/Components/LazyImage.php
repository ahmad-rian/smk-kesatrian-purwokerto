<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Services\ImageOptimizationService;

/**
 * Komponen Blade untuk gambar dengan lazy loading dan SEO optimization
 * 
 * Fitur yang disediakan:
 * - Auto-generate alt text yang SEO-friendly
 * - Lazy loading dengan placeholder
 * - WebP support dengan fallback
 * - Responsive image attributes
 * - Structured data untuk gambar
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class LazyImage extends Component
{
    /**
     * URL gambar utama
     */
    public string $src;
    
    /**
     * Alt text untuk gambar
     */
    public string $alt;
    
    /**
     * URL gambar WebP (opsional)
     */
    public ?string $webpSrc;
    
    /**
     * Class CSS tambahan
     */
    public string $class;
    
    /**
     * Konteks gambar untuk auto alt text
     */
    public ?string $context;
    
    /**
     * Judul terkait gambar
     */
    public ?string $title;
    
    /**
     * Apakah menggunakan lazy loading
     */
    public bool $lazy;
    
    /**
     * Placeholder image URL
     */
    public ?string $placeholder;
    
    /**
     * Service untuk optimasi gambar
     */
    private ImageOptimizationService $imageService;
    
    /**
     * Create a new component instance.
     */
    public function __construct(
        string $src,
        ?string $alt = null,
        ?string $webpSrc = null,
        string $class = 'img-responsive',
        ?string $context = null,
        ?string $title = null,
        bool $lazy = true,
        ?string $placeholder = null
    ) {
        $this->src = $src;
        $this->webpSrc = $webpSrc;
        $this->class = $class;
        $this->context = $context;
        $this->title = $title;
        $this->lazy = $lazy;
        $this->placeholder = $placeholder;
        
        $this->imageService = app(ImageOptimizationService::class);
        
        // Auto-generate alt text jika tidak disediakan
        if (!$alt) {
            $filename = basename($src);
            $this->alt = $this->imageService->generateAltText($filename, $context, $title);
        } else {
            $this->alt = $alt;
        }
    }
    
    /**
     * Get lazy loading attributes
     */
    public function getLazyAttributes(): array
    {
        $options = [
            'class' => $this->class,
            'responsive' => true
        ];
        
        if ($this->placeholder) {
            $options['placeholder'] = $this->placeholder;
        }
        
        return $this->imageService->getLazyLoadingAttributes($this->src, $this->alt, $options);
    }
    
    /**
     * Get structured data untuk gambar
     */
    public function getStructuredData(): array
    {
        $metadata = [];
        
        if ($this->title) {
            $metadata['caption'] = $this->title;
        }
        
        $metadata['author'] = 'SMK Kesatrian Purwokerto';
        
        return $this->imageService->getImageStructuredData($this->src, $this->alt, $metadata);
    }
    
    /**
     * Check if WebP is supported
     */
    public function hasWebpSupport(): bool
    {
        return !empty($this->webpSrc);
    }
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.lazy-image');
    }
}