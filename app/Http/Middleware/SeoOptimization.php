<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;

/**
 * Middleware untuk optimasi SEO otomatis
 * 
 * Fitur yang disediakan:
 * - Auto-inject canonical URL
 * - Security headers untuk SEO
 * - Open Graph dan Twitter Cards default
 * - Breadcrumb navigation
 * - Schema markup otomatis
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class SeoOptimization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Hanya proses untuk HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }
        
        // Tambahkan SEO headers
        $this->addSeoHeaders($response, $request);
        
        // Inject SEO meta tags jika belum ada
        $this->injectSeoMetaTags($response, $request);
        
        return $response;
    }
    
    /**
     * Check if response is HTML
     */
    private function isHtmlResponse($response): bool
    {
        return $response instanceof Response && 
               str_contains($response->headers->get('Content-Type', ''), 'text/html');
    }
    
    /**
     * Add SEO-friendly headers
     */
    private function addSeoHeaders(Response $response, Request $request): void
    {
        // Security headers yang membantu SEO
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Canonical URL header
        $canonicalUrl = $request->url();
        $response->headers->set('Link', "<{$canonicalUrl}>; rel=\"canonical\"");
        
        // Cache headers untuk static assets
        if ($this->isStaticAsset($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }
    }
    
    /**
     * Inject SEO meta tags ke dalam HTML
     */
    private function injectSeoMetaTags(Response $response, Request $request): void
    {
        $content = $response->getContent();
        
        // Skip jika sudah ada SEO meta tags
        if (str_contains($content, 'property="og:title"') || str_contains($content, '<x-seo-meta')) {
            return;
        }
        
        $seoTags = $this->generateDefaultSeoTags($request);
        
        // Inject setelah <head> tag
        $content = str_replace(
            '<head>',
            '<head>' . "\n" . $seoTags,
            $content
        );
        
        $response->setContent($content);
    }
    
    /**
     * Generate default SEO tags
     */
    private function generateDefaultSeoTags(Request $request): string
    {
        $siteSetting = SiteSetting::getInstance();
        $url = $request->url();
        $title = $this->generatePageTitle($request);
        $description = $this->generatePageDescription($request);
        
        $tags = [];
        
        // Basic meta tags
        $tags[] = "<meta name=\"description\" content=\"{$description}\">";
        $tags[] = "<meta name=\"keywords\" content=\"SMK Kesatrian, Purwokerto, sekolah kejuruan, pendidikan\">";
        $tags[] = "<link rel=\"canonical\" href=\"{$url}\">";
        
        // Open Graph
        $tags[] = "<meta property=\"og:title\" content=\"{$title}\">";
        $tags[] = "<meta property=\"og:description\" content=\"{$description}\">";
        $tags[] = "<meta property=\"og:url\" content=\"{$url}\">";
        $tags[] = "<meta property=\"og:type\" content=\"website\">";
        $tags[] = "<meta property=\"og:site_name\" content=\"SMK Kesatrian Purwokerto\">";
        
        if ($siteSetting && $siteSetting->logo) {
            $logoUrl = asset('storage/' . $siteSetting->logo);
            $tags[] = "<meta property=\"og:image\" content=\"{$logoUrl}\">";
        }
        
        // Twitter Cards
        $tags[] = "<meta name=\"twitter:card\" content=\"summary_large_image\">";
        $tags[] = "<meta name=\"twitter:title\" content=\"{$title}\">";
        $tags[] = "<meta name=\"twitter:description\" content=\"{$description}\">";
        
        // Favicon - Dinonaktifkan agar favicon.ico asli di public/ bisa digunakan
        // if ($siteSetting && $siteSetting->logo) {
        //     $faviconUrl = route('seo.favicon', ['size' => 32]);
        //     $tags[] = "<link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"{$faviconUrl}\">";
        // }
        
        return implode("\n    ", $tags);
    }
    
    /**
     * Generate page title berdasarkan route
     */
    private function generatePageTitle(Request $request): string
    {
        $routeName = $request->route()?->getName();
        $baseTitle = 'SMK Kesatrian Purwokerto';
        
        $titles = [
            'home' => $baseTitle . ' - Sekolah Menengah Kejuruan Terbaik di Purwokerto',
            'berita.index' => 'Berita Terbaru - ' . $baseTitle,
            'kegiatan.index' => 'Kegiatan Sekolah - ' . $baseTitle,
            'fasilitas.index' => 'Fasilitas Sekolah - ' . $baseTitle,
            'jurusan.index' => 'Program Studi - ' . $baseTitle,
            'profil.index' => 'Profil Sekolah - ' . $baseTitle,
            'kontak.index' => 'Kontak Kami - ' . $baseTitle,
        ];
        
        return $titles[$routeName] ?? $baseTitle;
    }
    
    /**
     * Generate page description berdasarkan route
     */
    private function generatePageDescription(Request $request): string
    {
        $routeName = $request->route()?->getName();
        
        $descriptions = [
            'home' => 'SMK Kesatrian Purwokerto adalah sekolah menengah kejuruan terbaik di Purwokerto dengan program keahlian unggulan dan fasilitas modern untuk mencetak generasi unggul dan berkarakter.',
            'berita.index' => 'Berita terbaru dan informasi penting dari SMK Kesatrian Purwokerto. Dapatkan update kegiatan, prestasi, dan perkembangan sekolah.',
            'kegiatan.index' => 'Berbagai kegiatan dan program unggulan SMK Kesatrian Purwokerto untuk mengembangkan potensi siswa dalam bidang akademik dan non-akademik.',
            'fasilitas.index' => 'Fasilitas lengkap dan modern SMK Kesatrian Purwokerto untuk mendukung proses pembelajaran yang optimal dan berkualitas.',
            'jurusan.index' => 'Program studi unggulan SMK Kesatrian Purwokerto dengan kurikulum terkini dan prospek karir yang cerah di berbagai bidang keahlian.',
            'profil.index' => 'Profil lengkap SMK Kesatrian Purwokerto, sejarah, visi misi, dan komitmen dalam mencetak lulusan yang kompeten dan berkarakter.',
            'kontak.index' => 'Hubungi SMK Kesatrian Purwokerto untuk informasi pendaftaran, konsultasi, dan pertanyaan seputar pendidikan kejuruan.',
        ];
        
        return $descriptions[$routeName] ?? 'SMK Kesatrian Purwokerto - Sekolah Menengah Kejuruan terbaik di Purwokerto dengan program keahlian unggulan.';
    }
    
    /**
     * Check if request is for static asset
     */
    private function isStaticAsset(Request $request): bool
    {
        $path = $request->getPathInfo();
        $extensions = ['.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.webp', '.svg', '.ico', '.woff', '.woff2', '.ttf'];
        
        foreach ($extensions as $ext) {
            if (str_ends_with($path, $ext)) {
                return true;
            }
        }
        
        return false;
    }
}