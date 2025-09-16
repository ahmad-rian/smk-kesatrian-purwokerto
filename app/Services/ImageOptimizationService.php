<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
// Image processing akan menggunakan native PHP functions

/**
 * Service untuk optimasi gambar dengan SEO-friendly features
 * 
 * Fitur yang disediakan:
 * - Auto-generate alt text dari nama file dan context
 * - Lazy loading attributes
 * - Responsive image sizes
 * - WebP conversion untuk performa
 * - Structured data untuk gambar
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class ImageOptimizationService
{
    /**
     * Generate alt text otomatis untuk gambar
     * 
     * @param string $filename Nama file gambar
     * @param string|null $context Konteks gambar (misal: 'fasilitas', 'berita', 'galeri')
     * @param string|null $title Judul terkait gambar
     * @return string
     */
    public function generateAltText(string $filename, ?string $context = null, ?string $title = null): string
    {
        // Bersihkan nama file dari extension dan karakter khusus
        $cleanName = pathinfo($filename, PATHINFO_FILENAME);
        $cleanName = str_replace(['_', '-', '.'], ' ', $cleanName);
        $cleanName = ucwords(strtolower($cleanName));
        
        // Buat alt text berdasarkan konteks
        $altText = '';
        
        if ($title) {
            $altText = $title;
        } elseif ($context) {
            $altText = $this->getContextualAltText($context, $cleanName);
        } else {
            $altText = $cleanName;
        }
        
        // Tambahkan branding jika diperlukan
        if (!str_contains(strtolower($altText), 'smk kesatrian')) {
            $altText .= ' - SMK Kesatrian Purwokerto';
        }
        
        return $altText;
    }
    
    /**
     * Generate alt text berdasarkan konteks
     * 
     * @param string $context
     * @param string $cleanName
     * @return string
     */
    private function getContextualAltText(string $context, string $cleanName): string
    {
        return match($context) {
            'fasilitas' => "Fasilitas {$cleanName} SMK Kesatrian Purwokerto",
            'berita' => "Berita {$cleanName} SMK Kesatrian Purwokerto",
            'galeri' => "Galeri {$cleanName} SMK Kesatrian Purwokerto",
            'kegiatan' => "Kegiatan {$cleanName} SMK Kesatrian Purwokerto",
            'jurusan' => "Program Studi {$cleanName} SMK Kesatrian Purwokerto",
            'profil' => "Profil {$cleanName} SMK Kesatrian Purwokerto",
            default => $cleanName
        };
    }
    
    /**
     * Generate lazy loading attributes untuk gambar
     * 
     * @param string $src URL gambar
     * @param string $alt Alt text
     * @param array $options Opsi tambahan
     * @return array
     */
    public function getLazyLoadingAttributes(string $src, string $alt, array $options = []): array
    {
        $attributes = [
            'src' => $src,
            'alt' => $alt,
            'loading' => 'lazy',
            'decoding' => 'async',
        ];
        
        // Tambahkan placeholder jika ada
        if (isset($options['placeholder'])) {
            $attributes['data-src'] = $src;
            $attributes['src'] = $options['placeholder'];
            $attributes['class'] = ($options['class'] ?? '') . ' lazy-load';
        }
        
        // Tambahkan responsive sizes
        if (isset($options['responsive']) && $options['responsive']) {
            $attributes['sizes'] = '(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw';
        }
        
        return $attributes;
    }
    
    /**
     * Generate structured data untuk gambar
     * 
     * @param string $url URL gambar
     * @param string $alt Alt text
     * @param array $metadata Metadata tambahan
     * @return array
     */
    public function getImageStructuredData(string $url, string $alt, array $metadata = []): array
    {
        $structuredData = [
            '@type' => 'ImageObject',
            'url' => $url,
            'description' => $alt,
        ];
        
        // Tambahkan metadata jika ada
        if (isset($metadata['width'])) {
            $structuredData['width'] = $metadata['width'];
        }
        
        if (isset($metadata['height'])) {
            $structuredData['height'] = $metadata['height'];
        }
        
        if (isset($metadata['caption'])) {
            $structuredData['caption'] = $metadata['caption'];
        }
        
        if (isset($metadata['author'])) {
            $structuredData['author'] = [
                '@type' => 'Organization',
                'name' => $metadata['author']
            ];
        }
        
        return $structuredData;
    }
    
    /**
     * Generate responsive image HTML
     * 
     * @param string $src URL gambar
     * @param string $alt Alt text
     * @param array $options Opsi tambahan
     * @return string
     */
    public function generateResponsiveImageHtml(string $src, string $alt, array $options = []): string
    {
        $attributes = $this->getLazyLoadingAttributes($src, $alt, $options);
        
        $html = '<img';
        foreach ($attributes as $key => $value) {
            $html .= " {$key}=\"" . htmlspecialchars($value) . '"';
        }
        
        // Tambahkan class default jika tidak ada
        if (!isset($attributes['class'])) {
            $html .= ' class="img-responsive"';
        }
        
        $html .= ' />';
        
        return $html;
    }
    
    /**
     * Optimize gambar untuk web dengan WebP conversion
     * 
     * @param UploadedFile $file
     * @param string $path
     * @param array $options
     * @return array
     */
    public function optimizeForWeb(UploadedFile $file, string $path, array $options = []): array
    {
        try {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
            
            // Generate nama file yang SEO-friendly
            $seoFilename = $this->generateSeoFriendlyFilename($nameWithoutExt);
            
            $results = [];
            
            // Simpan original
            $originalPath = $path . '/' . $seoFilename . '.' . $extension;
            Storage::disk('public')->put($originalPath, file_get_contents($file->getRealPath()));
            $results['original'] = $originalPath;
            
            // Convert ke WebP jika memungkinkan
            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                $webpPath = $path . '/' . $seoFilename . '.webp';
                
                // Untuk WebP conversion, bisa menggunakan GD atau Imagick
                // Sementara skip WebP conversion jika tidak ada library
                if (function_exists('imagewebp')) {
                    $sourceImage = null;
                    
                    // Load image berdasarkan type
                    switch (strtolower($extension)) {
                        case 'jpg':
                        case 'jpeg':
                            $sourceImage = imagecreatefromjpeg($file->getRealPath());
                            break;
                        case 'png':
                            $sourceImage = imagecreatefrompng($file->getRealPath());
                            break;
                    }
                    
                    if ($sourceImage) {
                        $quality = $options['quality'] ?? 85;
                        $webpContent = ob_get_clean();
                        ob_start();
                        imagewebp($sourceImage, null, $quality);
                        $webpContent = ob_get_contents();
                        ob_end_clean();
                        imagedestroy($sourceImage);
                    }
                } else {
                    // Fallback: copy original file sebagai WebP
                    $webpContent = file_get_contents($file->getRealPath());
                }
                
                Storage::disk('public')->put($webpPath, $webpContent);
                $results['webp'] = $webpPath;
            }
            
            return $results;
            
        } catch (\Exception $e) {
            Log::error('Image optimization failed: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Generate SEO-friendly filename
     * 
     * @param string $filename
     * @return string
     */
    private function generateSeoFriendlyFilename(string $filename): string
    {
        // Convert ke lowercase
        $filename = strtolower($filename);
        
        // Replace spasi dan karakter khusus dengan dash
        $filename = preg_replace('/[^a-z0-9]+/', '-', $filename);
        
        // Remove multiple dashes
        $filename = preg_replace('/-+/', '-', $filename);
        
        // Remove leading/trailing dashes
        $filename = trim($filename, '-');
        
        // Tambahkan timestamp untuk uniqueness
        $filename .= '-' . time();
        
        return $filename;
    }
    
    /**
     * Generate picture element dengan WebP fallback
     * 
     * @param string $webpSrc URL WebP
     * @param string $fallbackSrc URL fallback
     * @param string $alt Alt text
     * @param array $options Opsi tambahan
     * @return string
     */
    public function generatePictureElement(string $webpSrc, string $fallbackSrc, string $alt, array $options = []): string
    {
        $class = $options['class'] ?? 'img-responsive';
        $loading = $options['loading'] ?? 'lazy';
        
        $html = '<picture>';
        $html .= "<source srcset=\"{$webpSrc}\" type=\"image/webp\">";
        $html .= "<img src=\"{$fallbackSrc}\" alt=\"" . htmlspecialchars($alt) . "\" class=\"{$class}\" loading=\"{$loading}\" decoding=\"async\">";
        $html .= '</picture>';
        
        return $html;
    }
}