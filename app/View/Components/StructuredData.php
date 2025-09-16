<?php

namespace App\View\Components;

use App\Services\StructuredDataService;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

/**
 * Component untuk menampilkan structured data (JSON-LD)
 * Membantu SEO dan pemahaman AI terhadap konten situs
 */
class StructuredData extends Component
{
    public string $pageType;
    public array $data;
    public bool $includeDefaults;

    /**
     * Create a new component instance.
     *
     * @param string $pageType Tipe halaman (home, article, course, event, faq, contact)
     * @param array $data Data spesifik untuk schema
     * @param bool $includeDefaults Apakah menyertakan schema default (Organization, WebSite)
     */
    public function __construct(
        string $pageType = 'home',
        array $data = [],
        bool $includeDefaults = true
    ) {
        $this->pageType = $pageType;
        $this->data = $data;
        $this->includeDefaults = $includeDefaults;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        $structuredDataService = new StructuredDataService();
        
        // Generate schemas berdasarkan page type dan data
        $schemas = $this->includeDefaults 
            ? $structuredDataService->generatePageSchemas($this->pageType, $this->data)
            : $this->generateSpecificSchemas($structuredDataService);

        return view('components.structured-data', [
            'schemas' => $schemas
        ]);
    }

    /**
     * Generate schema spesifik tanpa default schemas
     */
    private function generateSpecificSchemas(StructuredDataService $service): array
    {
        $schemas = [];
        
        switch ($this->pageType) {
            case 'article':
                if (!empty($this->data['article'])) {
                    $schemas[] = $service->generateArticleSchema($this->data['article']);
                }
                break;
                
            case 'course':
                if (!empty($this->data['jurusan'])) {
                    $schemas[] = $service->generateCourseSchema($this->data['jurusan']);
                }
                break;
                
            case 'event':
                if (!empty($this->data['event'])) {
                    $schemas[] = $service->generateEventSchema($this->data['event']);
                }
                break;
                
            case 'faq':
                if (!empty($this->data['faqs'])) {
                    $schemas[] = $service->generateFAQSchema($this->data['faqs']);
                }
                break;
                
            case 'breadcrumb':
                if (!empty($this->data['breadcrumbs'])) {
                    $schemas[] = $service->generateBreadcrumbSchema($this->data['breadcrumbs']);
                }
                break;
        }
        
        return $schemas;
    }

    /**
     * Helper method untuk membuat data artikel
     */
    public static function articleData(object $article, string $baseUrl = ''): array
    {
        return [
            'article' => [
                'title' => $article->judul ?? $article->title ?? '',
                'excerpt' => $article->excerpt ?? $article->ringkasan ?? '',
                'url' => $baseUrl . '/berita/' . ($article->slug ?? ''),
                'created_at' => $article->created_at,
                'updated_at' => $article->updated_at,
                'featured_image' => $article->featured_image ?? $article->gambar ?? null,
                'category' => $article->kategori ?? $article->category ?? null,
                'tags' => $article->tags ?? []
            ]
        ];
    }

    /**
     * Helper method untuk membuat data jurusan
     */
    public static function courseData(object $jurusan, string $baseUrl = ''): array
    {
        return [
            'jurusan' => [
                'nama' => $jurusan->nama ?? $jurusan->name ?? '',
                'deskripsi' => $jurusan->deskripsi ?? $jurusan->description ?? '',
                'url' => $baseUrl . '/jurusan/' . ($jurusan->slug ?? ''),
                'kategori' => $jurusan->kategori ?? 'Technical Education',
                'kompetensi' => $jurusan->kompetensi ?? $jurusan->skills ?? []
            ]
        ];
    }

    /**
     * Helper method untuk membuat data event
     */
    public static function eventData(object $event, string $baseUrl = ''): array
    {
        return [
            'event' => [
                'nama' => $event->nama ?? $event->title ?? '',
                'deskripsi' => $event->deskripsi ?? $event->description ?? '',
                'url' => $baseUrl . '/kegiatan/' . ($event->slug ?? ''),
                'tanggal_mulai' => $event->tanggal_mulai ?? $event->start_date ?? null,
                'tanggal_selesai' => $event->tanggal_selesai ?? $event->end_date ?? null,
                'lokasi' => $event->lokasi ?? $event->location ?? null,
                'gambar' => $event->gambar ?? $event->image ?? null
            ]
        ];
    }

    /**
     * Helper method untuk membuat data breadcrumb
     */
    public static function breadcrumbData(array $breadcrumbs): array
    {
        return [
            'breadcrumbs' => $breadcrumbs
        ];
    }

    /**
     * Helper method untuk membuat data FAQ
     */
    public static function faqData(array $faqs): array
    {
        return [
            'faqs' => $faqs
        ];
    }
}