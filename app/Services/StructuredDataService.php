<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * Service untuk generate structured data (JSON-LD)
 * Membantu Google dan AI memahami konten situs dengan lebih baik
 */
class StructuredDataService
{
    private SiteSetting $siteSetting;

    public function __construct()
    {
        $this->siteSetting = SiteSetting::getInstance();
    }

    /**
     * Generate Organization schema untuk sekolah
     */
    public function generateOrganizationSchema(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            'name' => $this->siteSetting->nama_sekolah,
            'alternateName' => $this->siteSetting->nama_singkat ?? $this->siteSetting->nama_sekolah,
            'url' => $this->siteSetting->website ?: URL::to('/'),
            'logo' => $this->getLogoUrl(),
            'description' => $this->siteSetting->deskripsi_sekolah ?? 'Sekolah Menengah Kejuruan yang menghasilkan lulusan berkualitas dan berkarakter.',
        ];

        // Tambahkan alamat jika ada
        if ($this->siteSetting->alamat) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->siteSetting->alamat,
                'addressLocality' => $this->siteSetting->kota ?? 'Indonesia',
                'addressCountry' => 'ID'
            ];
        }

        // Tambahkan kontak jika ada
        $contactPoints = [];
        if ($this->siteSetting->telepon) {
            $contactPoints[] = [
                '@type' => 'ContactPoint',
                'telephone' => $this->siteSetting->telepon,
                'contactType' => 'customer service',
                'availableLanguage' => ['Indonesian']
            ];
        }

        if ($this->siteSetting->email) {
            $contactPoints[] = [
                '@type' => 'ContactPoint',
                'email' => $this->siteSetting->email,
                'contactType' => 'customer service',
                'availableLanguage' => ['Indonesian']
            ];
        }

        if (!empty($contactPoints)) {
            $schema['contactPoint'] = $contactPoints;
        }

        // Tambahkan social media jika ada
        $sameAs = [];
        if ($this->siteSetting->facebook) {
            $sameAs[] = $this->siteSetting->facebook;
        }
        if ($this->siteSetting->instagram) {
            $sameAs[] = $this->siteSetting->instagram;
        }
        if ($this->siteSetting->youtube) {
            $sameAs[] = $this->siteSetting->youtube;
        }
        if ($this->siteSetting->twitter) {
            $sameAs[] = $this->siteSetting->twitter;
        }

        if (!empty($sameAs)) {
            $schema['sameAs'] = $sameAs;
        }

        // Tambahkan informasi pendidikan
        $schema['educationalCredentialAwarded'] = 'Diploma SMK';
        $schema['hasCredential'] = [
            '@type' => 'EducationalOccupationalCredential',
            'credentialCategory' => 'Diploma',
            'educationalLevel' => 'Secondary Education'
        ];

        return $schema;
    }

    /**
     * Generate WebSite schema dengan search action
     */
    public function generateWebSiteSchema(): array
    {
        $baseUrl = $this->siteSetting->website ?: URL::to('/');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $this->siteSetting->nama_sekolah,
            'url' => $baseUrl,
            'description' => $this->siteSetting->deskripsi_sekolah ?? 'Website resmi ' . $this->siteSetting->nama_sekolah,
            'publisher' => [
                '@type' => 'EducationalOrganization',
                'name' => $this->siteSetting->nama_sekolah,
                'logo' => $this->getLogoUrl()
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $baseUrl . '/search?q={search_term_string}'
                ],
                'query-input' => 'required name=search_term_string'
            ]
        ];
    }

    /**
     * Generate BreadcrumbList schema
     */
    public function generateBreadcrumbSchema(array $breadcrumbs): array
    {
        $listItems = [];
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $listItems[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['title'],
                'item' => $breadcrumb['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems
        ];
    }

    /**
     * Generate Article schema untuk berita
     */
    public function generateArticleSchema(array $article): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article['title'],
            'description' => $article['excerpt'] ?? '',
            'url' => $article['url'],
            'datePublished' => Carbon::parse($article['created_at'])->toISOString(),
            'dateModified' => Carbon::parse($article['updated_at'])->toISOString(),
            'author' => [
                '@type' => 'Organization',
                'name' => $this->siteSetting->nama_sekolah,
                'url' => $this->siteSetting->website ?: URL::to('/')
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->siteSetting->nama_sekolah,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $this->getLogoUrl()
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $article['url']
            ]
        ];

        // Tambahkan gambar jika ada
        if (!empty($article['featured_image'])) {
            $schema['image'] = [
                '@type' => 'ImageObject',
                'url' => Storage::url($article['featured_image']),
                'width' => 1200,
                'height' => 630
            ];
        }

        // Tambahkan kategori jika ada
        if (!empty($article['category'])) {
            $schema['articleSection'] = $article['category'];
        }

        // Tambahkan keywords jika ada
        if (!empty($article['tags'])) {
            $schema['keywords'] = is_array($article['tags']) 
                ? implode(', ', $article['tags']) 
                : $article['tags'];
        }

        return $schema;
    }

    /**
     * Generate Course schema untuk jurusan
     */
    public function generateCourseSchema(array $jurusan): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $jurusan['nama'],
            'description' => $jurusan['deskripsi'] ?? '',
            'url' => $jurusan['url'],
            'provider' => [
                '@type' => 'EducationalOrganization',
                'name' => $this->siteSetting->nama_sekolah,
                'url' => $this->siteSetting->website ?: URL::to('/')
            ],
            'educationalCredentialAwarded' => 'Diploma SMK ' . $jurusan['nama'],
            'courseMode' => 'full-time',
            'timeRequired' => 'P3Y', // 3 tahun
            'occupationalCategory' => $jurusan['kategori'] ?? 'Technical Education',
            'coursePrerequisites' => 'Lulus SMP/MTs',
            'educationalLevel' => 'Secondary Education',
            'teaches' => $jurusan['kompetensi'] ?? []
        ];
    }

    /**
     * Generate Event schema untuk kegiatan sekolah
     */
    public function generateEventSchema(array $event): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event['nama'],
            'description' => $event['deskripsi'] ?? '',
            'url' => $event['url'],
            'organizer' => [
                '@type' => 'EducationalOrganization',
                'name' => $this->siteSetting->nama_sekolah,
                'url' => $this->siteSetting->website ?: URL::to('/')
            ]
        ];

        // Tambahkan tanggal jika ada
        if (!empty($event['tanggal_mulai'])) {
            $schema['startDate'] = Carbon::parse($event['tanggal_mulai'])->toISOString();
        }

        if (!empty($event['tanggal_selesai'])) {
            $schema['endDate'] = Carbon::parse($event['tanggal_selesai'])->toISOString();
        }

        // Tambahkan lokasi jika ada
        if (!empty($event['lokasi'])) {
            $schema['location'] = [
                '@type' => 'Place',
                'name' => $event['lokasi'],
                'address' => $this->siteSetting->alamat ?? ''
            ];
        }

        // Tambahkan gambar jika ada
        if (!empty($event['gambar'])) {
            $schema['image'] = Storage::url($event['gambar']);
        }

        return $schema;
    }

    /**
     * Generate FAQ schema
     */
    public function generateFAQSchema(array $faqs): array
    {
        $mainEntity = [];
        
        foreach ($faqs as $faq) {
            $mainEntity[] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                ]
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $mainEntity
        ];
    }

    /**
     * Generate LocalBusiness schema untuk sekolah
     */
    public function generateLocalBusinessSchema(): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'School',
            'name' => $this->siteSetting->nama_sekolah,
            'url' => $this->siteSetting->website ?: URL::to('/'),
            'logo' => $this->getLogoUrl(),
            'description' => $this->siteSetting->deskripsi_sekolah ?? 'Sekolah Menengah Kejuruan berkualitas'
        ];

        // Tambahkan alamat dan geo jika ada
        if ($this->siteSetting->alamat) {
            $schema['address'] = [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->siteSetting->alamat,
                'addressLocality' => $this->siteSetting->kota ?? '',
                'addressCountry' => 'ID'
            ];
        }

        // Tambahkan koordinat jika ada
        if ($this->siteSetting->latitude && $this->siteSetting->longitude) {
            $schema['geo'] = [
                '@type' => 'GeoCoordinates',
                'latitude' => $this->siteSetting->latitude,
                'longitude' => $this->siteSetting->longitude
            ];
        }

        // Tambahkan jam operasional jika ada
        if ($this->siteSetting->jam_operasional) {
            $schema['openingHours'] = $this->siteSetting->jam_operasional;
        }

        return $schema;
    }

    /**
     * Get logo URL dengan fallback
     */
    private function getLogoUrl(): string
    {
        if ($this->siteSetting->logo && Storage::exists('public/' . $this->siteSetting->logo)) {
            return Storage::url($this->siteSetting->logo);
        }
        
        // Fallback ke favicon atau default
        return URL::to('/favicon-192.png');
    }

    /**
     * Generate semua schema untuk halaman tertentu
     */
    public function generatePageSchemas(string $pageType, array $data = []): array
    {
        $schemas = [];
        
        // Selalu tambahkan Organization dan WebSite schema
        $schemas[] = $this->generateOrganizationSchema();
        $schemas[] = $this->generateWebSiteSchema();
        
        // Tambahkan breadcrumb jika ada
        if (!empty($data['breadcrumbs'])) {
            $schemas[] = $this->generateBreadcrumbSchema($data['breadcrumbs']);
        }
        
        // Tambahkan schema spesifik berdasarkan tipe halaman
        switch ($pageType) {
            case 'article':
                if (!empty($data['article'])) {
                    $schemas[] = $this->generateArticleSchema($data['article']);
                }
                break;
                
            case 'course':
                if (!empty($data['jurusan'])) {
                    $schemas[] = $this->generateCourseSchema($data['jurusan']);
                }
                break;
                
            case 'event':
                if (!empty($data['event'])) {
                    $schemas[] = $this->generateEventSchema($data['event']);
                }
                break;
                
            case 'faq':
                if (!empty($data['faqs'])) {
                    $schemas[] = $this->generateFAQSchema($data['faqs']);
                }
                break;
                
            case 'contact':
                $schemas[] = $this->generateLocalBusinessSchema();
                break;
        }
        
        return $schemas;
    }
}