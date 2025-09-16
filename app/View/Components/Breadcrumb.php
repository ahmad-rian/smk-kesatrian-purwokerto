<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Breadcrumb Component dengan Schema Markup
 * 
 * Fitur:
 * - Generate breadcrumb otomatis berdasarkan URL
 * - Schema.org BreadcrumbList markup untuk SEO
 * - Customizable breadcrumb items
 * - Support untuk custom labels dan URLs
 * - Responsive design dengan MaryUI
 * - Icon support untuk setiap breadcrumb item
 */
class Breadcrumb extends Component
{
    public array $items;
    public bool $showHome;
    public string $homeLabel;
    public string $homeIcon;
    public string $separator;
    public bool $showStructuredData;
    public string $containerClass;
    
    /**
     * Create a new component instance.
     */
    public function __construct(
        array $items = [],
        bool $showHome = true,
        string $homeLabel = 'Beranda',
        string $homeIcon = 'o-home',
        string $separator = 'chevron-right',
        bool $showStructuredData = true,
        string $containerClass = 'breadcrumbs text-sm'
    ) {
        $this->items = $this->processItems($items);
        $this->showHome = $showHome;
        $this->homeLabel = $homeLabel;
        $this->homeIcon = $homeIcon;
        $this->separator = $separator;
        $this->showStructuredData = $showStructuredData;
        $this->containerClass = $containerClass;
    }
    
    /**
     * Process breadcrumb items
     */
    private function processItems(array $customItems): array
    {
        $items = [];
        
        // Tambahkan home jika diaktifkan
        if ($this->showHome) {
            $items[] = [
                'label' => $this->homeLabel,
                'url' => URL::to('/'),
                'icon' => $this->homeIcon,
                'active' => false
            ];
        }
        
        // Jika ada custom items, gunakan itu
        if (!empty($customItems)) {
            foreach ($customItems as $index => $item) {
                $items[] = [
                    'label' => $item['label'] ?? 'Unknown',
                    'url' => $item['url'] ?? null,
                    'icon' => $item['icon'] ?? null,
                    'active' => $item['active'] ?? ($index === count($customItems) - 1)
                ];
            }
        } else {
            // Generate otomatis dari URL segments
            $items = array_merge($items, $this->generateFromUrl());
        }
        
        return $items;
    }
    
    /**
     * Generate breadcrumb dari URL segments
     */
    private function generateFromUrl(): array
    {
        $segments = Request::segments();
        $items = [];
        $url = '';
        
        foreach ($segments as $index => $segment) {
            $url .= '/' . $segment;
            $isLast = $index === count($segments) - 1;
            
            $items[] = [
                'label' => $this->formatSegmentLabel($segment, $url),
                'url' => $isLast ? null : URL::to($url), // URL null untuk item terakhir (current page)
                'icon' => $this->getSegmentIcon($segment),
                'active' => $isLast
            ];
        }
        
        return $items;
    }
    
    /**
     * Format segment menjadi label yang readable
     */
    private function formatSegmentLabel(string $segment, string $url): string
    {
        // Custom labels untuk route tertentu
        $customLabels = [
            'admin' => 'Admin',
            'dashboard' => 'Dashboard',

            'site-settings' => 'Pengaturan Situs',
            'study-programs' => 'Program Studi',
            'school-activities' => 'Kegiatan Sekolah',
            'news' => 'Berita',
            'facilities' => 'Fasilitas',
            'galleries' => 'Galeri',
            'home-carousels' => 'Carousel Beranda',
            'contact-messages' => 'Pesan Kontak',
            'users' => 'Pengguna',
            'settings' => 'Pengaturan',
            'profile' => 'Profil',
            'password' => 'Password',
            'appearance' => 'Tampilan',
            'create' => 'Tambah Baru',
            'edit' => 'Edit',
            'show' => 'Detail',
            'index' => 'Daftar',
            'berita' => 'Berita',
            'kegiatan' => 'Kegiatan',
            'fasilitas' => 'Fasilitas',
            'jurusan' => 'Program Studi',
            'profil' => 'Profil',
            'kontak' => 'Kontak',
            'galeri' => 'Galeri'
        ];
        
        // Cek apakah ada custom label
        if (isset($customLabels[$segment])) {
            return $customLabels[$segment];
        }
        
        // Coba ambil dari route name jika tersedia
        $routeName = Request::route()?->getName();
        if ($routeName) {
            $routeParts = explode('.', $routeName);
            $lastPart = end($routeParts);
            if (isset($customLabels[$lastPart])) {
                return $customLabels[$lastPart];
            }
        }
        
        // Format default: replace dash dengan space dan capitalize
        return Str::title(str_replace(['-', '_'], ' ', $segment));
    }
    
    /**
     * Get icon untuk segment tertentu
     */
    private function getSegmentIcon(string $segment): ?string
    {
        $icons = [
            'admin' => 'o-cog-6-tooth',
            'dashboard' => 'o-squares-2x2',

            'site-settings' => 'o-cog-8-tooth',
            'study-programs' => 'o-academic-cap',
            'school-activities' => 'o-calendar-days',
            'news' => 'o-newspaper',
            'facilities' => 'o-building-office-2',
            'galleries' => 'o-photo',
            'home-carousels' => 'o-photo',
            'contact-messages' => 'o-envelope',
            'users' => 'o-users',
            'settings' => 'o-cog-6-tooth',
            'profile' => 'o-user',
            'password' => 'o-key',
            'appearance' => 'o-paint-brush',
            'create' => 'o-plus',
            'edit' => 'o-pencil',
            'show' => 'o-eye',
            'berita' => 'o-newspaper',
            'kegiatan' => 'o-calendar-days',
            'fasilitas' => 'o-building-office-2',
            'jurusan' => 'o-academic-cap',
            'profil' => 'o-information-circle',
            'kontak' => 'o-phone',
            'galeri' => 'o-photo'
        ];
        
        return $icons[$segment] ?? null;
    }
    
    /**
     * Get structured data untuk breadcrumb
     */
    public function getStructuredData(): ?string
    {
        if (!$this->showStructuredData || empty($this->items)) {
            return null;
        }
        
        $itemListElement = [];
        
        foreach ($this->items as $index => $item) {
            $element = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label']
            ];
            
            // Tambahkan URL jika bukan item terakhir
            if (!$item['active'] && $item['url']) {
                $element['item'] = $item['url'];
            }
            
            $itemListElement[] = $element;
        }
        
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $itemListElement
        ];
        
        return json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Check apakah breadcrumb harus ditampilkan
     */
    public function shouldShow(): bool
    {
        // Jangan tampilkan di halaman home jika hanya ada home item
        if (Request::is('/') && count($this->items) <= 1) {
            return false;
        }
        
        return !empty($this->items);
    }
    
    /**
     * Get breadcrumb items untuk JSON-LD
     */
    public function getJsonLdItems(): array
    {
        $items = [];
        
        foreach ($this->items as $index => $item) {
            $jsonItem = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['label']
            ];
            
            if ($item['url']) {
                $jsonItem['item'] = $item['url'];
            }
            
            $items[] = $jsonItem;
        }
        
        return $items;
    }
    
    /**
     * Get current page title dari breadcrumb
     */
    public function getCurrentPageTitle(): ?string
    {
        if (empty($this->items)) {
            return null;
        }
        
        $lastItem = end($this->items);
        return $lastItem['active'] ? $lastItem['label'] : null;
    }
    
    /**
     * Get parent page info
     */
    public function getParentPage(): ?array
    {
        if (count($this->items) < 2) {
            return null;
        }
        
        $parentIndex = count($this->items) - 2;
        return $this->items[$parentIndex] ?? null;
    }
    
    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.breadcrumb');
    }
}