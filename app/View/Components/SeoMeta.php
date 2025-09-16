<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * SEO Meta Component untuk optimasi mesin pencari
 * 
 * Komponen ini menghasilkan metadata lengkap untuk SEO termasuk:
 * - Meta tags dasar (title, description, keywords)
 * - Open Graph tags untuk media sosial
 * - Twitter Cards
 * - Structured data JSON-LD
 * - Favicon otomatis dari logo situs
 * - Canonical URL
 * - Robots meta
 */
class SeoMeta extends Component
{
    /**
     * Judul halaman
     */
    public string $title;
    
    /**
     * Deskripsi halaman
     */
    public string $description;
    
    /**
     * Keywords untuk halaman
     */
    public string $keywords;
    
    /**
     * URL gambar untuk Open Graph
     */
    public ?string $image;
    
    /**
     * Tipe konten (website, article, etc)
     */
    public string $type;
    
    /**
     * URL canonical
     */
    public string $canonical;
    
    /**
     * Data pengaturan situs
     */
    public $siteSetting;
    
    /**
     * Robots directive
     */
    public string $robots;
    
    /**
     * Author information
     */
    public ?string $author;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $title = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $image = null,
        string $type = 'website',
        ?string $canonical = null,
        string $robots = 'index, follow',
        ?string $author = null
    ) {
        $this->siteSetting = SiteSetting::getInstance();
        
        // Set title dengan fallback ke nama sekolah
        $this->title = $title 
            ? $title . ' - ' . $this->siteSetting->nama_sekolah
            : $this->siteSetting->nama_sekolah . ($this->siteSetting->tagline ? ' - ' . $this->siteSetting->tagline : '');
        
        // Set description dengan fallback ke deskripsi situs
        $this->description = $description ?: ($this->siteSetting->deskripsi ?: 'SMK terbaik dengan pendidikan berkualitas dan fasilitas modern');
        
        // Generate keywords otomatis jika tidak disediakan
        $this->keywords = $keywords ?: $this->generateKeywords();
        
        // Set image dengan fallback ke logo situs
        $this->image = $image ?: ($this->siteSetting->logo_url ? URL::to($this->siteSetting->logo_url) : null);
        
        $this->type = $type;
        $this->canonical = $canonical ?: URL::current();
        $this->robots = $robots;
        $this->author = $author ?: $this->siteSetting->nama_sekolah;
    }

    /**
     * Generate keywords otomatis berdasarkan data situs
     */
    private function generateKeywords(): string
    {
        $keywords = [];
        
        // Keywords dasar
        $keywords[] = 'SMK';
        $keywords[] = 'sekolah menengah kejuruan';
        $keywords[] = 'pendidikan vokasi';
        
        // Dari nama sekolah
        if ($this->siteSetting->nama_sekolah) {
            $keywords[] = $this->siteSetting->nama_sekolah;
            $keywords[] = $this->siteSetting->nama_singkat ?: Str::words($this->siteSetting->nama_sekolah, 2, '');
        }
        
        // Dari lokasi (extract dari alamat)
        if ($this->siteSetting->alamat) {
            $alamatWords = explode(' ', $this->siteSetting->alamat);
            foreach ($alamatWords as $word) {
                if (strlen($word) > 3 && !in_array(strtolower($word), ['jalan', 'jl.', 'no.', 'rt.', 'rw.'])) {
                    $keywords[] = $word;
                }
            }
        }
        
        // Keywords tambahan untuk SMK
        $additionalKeywords = [
            'lulusan siap kerja',
            'keterampilan teknis',
            'pendidikan karakter',
            'fasilitas modern',
            'tenaga pengajar kompeten',
            'sertifikasi profesi',
            'magang industri',
            'wirausaha muda'
        ];
        
        $keywords = array_merge($keywords, $additionalKeywords);
        
        return implode(', ', array_unique(array_filter($keywords)));
    }
    
    /**
     * Generate favicon URLs dari logo situs
     */
    public function getFaviconUrls(): array
    {
        $favicons = [];
        
        if ($this->siteSetting->logo_url) {
            $logoUrl = URL::to($this->siteSetting->logo_url);
            
            // Generate berbagai ukuran favicon
            $favicons = [
                'icon' => $logoUrl,
                'apple-touch-icon' => $logoUrl,
                'icon-32' => $logoUrl,
                'icon-16' => $logoUrl,
                'icon-192' => $logoUrl,
                'icon-512' => $logoUrl,
            ];
        }
        
        return $favicons;
    }
    
    /**
     * Generate structured data JSON-LD untuk sekolah
     */
    public function getStructuredData(): array
    {
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => $this->siteSetting->nama_sekolah,
            'alternateName' => $this->siteSetting->nama_singkat,
            'description' => $this->description,
            'url' => $this->siteSetting->website ?: URL::to('/'),
            'logo' => $this->image,
            'image' => $this->image,
            'foundingDate' => $this->siteSetting->tahun_berdiri,
            'slogan' => $this->siteSetting->tagline,
        ];
        
        // Tambahkan alamat jika ada
        if ($this->siteSetting->alamat) {
            $structuredData['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->siteSetting->alamat,
            ];
        }
        
        // Tambahkan kontak jika ada
        $contactPoint = [];
        if ($this->siteSetting->telepon) {
            $contactPoint['telephone'] = $this->siteSetting->telepon;
        }
        if ($this->siteSetting->email) {
            $contactPoint['email'] = $this->siteSetting->email;
        }
        
        if (!empty($contactPoint)) {
            $contactPoint['@type'] = 'ContactPoint';
            $contactPoint['contactType'] = 'customer service';
            $structuredData['contactPoint'] = $contactPoint;
        }
        
        // Tambahkan media sosial jika ada
        if ($this->siteSetting->media_sosial) {
            $socialUrls = [];
            foreach ($this->siteSetting->media_sosial as $platform => $handle) {
                if ($handle) {
                    switch ($platform) {
                        case 'instagram':
                            $socialUrls[] = 'https://instagram.com/' . ltrim($handle, '@');
                            break;
                        case 'facebook':
                            $socialUrls[] = 'https://facebook.com/' . $handle;
                            break;
                        case 'youtube':
                            $socialUrls[] = 'https://youtube.com/@' . $handle;
                            break;
                        case 'tiktok':
                            $socialUrls[] = 'https://tiktok.com/@' . ltrim($handle, '@');
                            break;
                    }
                }
            }
            if (!empty($socialUrls)) {
                $structuredData['sameAs'] = $socialUrls;
            }
        }
        
        // Tambahkan informasi kepala sekolah jika ada
        if ($this->siteSetting->nama_kepala_sekolah) {
            $structuredData['employee'] = [
                '@type' => 'Person',
                'name' => $this->siteSetting->nama_kepala_sekolah,
                'jobTitle' => 'Kepala Sekolah',
                'image' => $this->siteSetting->foto_kepala_sekolah_url ? URL::to($this->siteSetting->foto_kepala_sekolah_url) : null,
            ];
        }
        
        return $structuredData;
    }
    
    /**
     * Get breadcrumb structured data
     */
    public function getBreadcrumbData(): ?array
    {
        $segments = request()->segments();
        
        if (empty($segments)) {
            return null;
        }
        
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];
        
        // Home breadcrumb
        $breadcrumbs['itemListElement'][] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Beranda',
            'item' => URL::to('/')
        ];
        
        // Dynamic breadcrumbs
        $url = URL::to('/');
        foreach ($segments as $index => $segment) {
            $url .= '/' . $segment;
            $breadcrumbs['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 2,
                'name' => ucfirst(str_replace('-', ' ', $segment)),
                'item' => $url
            ];
        }
        
        return $breadcrumbs;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.seo-meta');
    }
}