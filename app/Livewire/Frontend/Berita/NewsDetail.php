<?php

namespace App\Livewire\Frontend\Berita;

use App\Models\News;
use Livewire\Component;

/**
 * Livewire Component untuk Detail Berita Frontend
 * 
 * Fitur:
 * - Menampilkan detail berita berdasarkan slug
 * - Menampilkan berita terkait
 * - SEO friendly dengan meta tags
 * - Responsive design
 */
class NewsDetail extends Component
{
    // Properties untuk berita
    public $newsSlug;
    public $news;
    public $relatedNews = [];

    /**
     * Mount component dengan slug berita
     */
    public function mount(string $slug)
    {
        $this->newsSlug = $slug;
        $this->loadNews();

        // Only load related news if main news is found
        if ($this->news) {
            $this->loadRelatedNews();
        }
    }

    /**
     * Load data berita berdasarkan slug
     */
    private function loadNews(): void
    {
        $this->news = News::where('slug', $this->newsSlug)
            ->where('status', 'published')
            ->first();

        if (!$this->news) {
            abort(404, 'Berita tidak ditemukan');
        }
    }

    /**
     * Load berita terkait (3 berita terbaru selain yang sedang dibaca)
     */
    private function loadRelatedNews(): void
    {
        $this->relatedNews = News::where('status', 'published')
            ->where('id', '!=', $this->news->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }

    /**
     * Get formatted date
     */
    public function getFormattedDate($date)
    {
        return $date->format('d F Y');
    }

    /**
     * Get excerpt dari konten
     */
    public function getExcerpt($content, $limit = 150)
    {
        return strlen($content) > $limit ? substr($content, 0, $limit) . '...' : $content;
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.frontend.berita.news-detail', [
            'news' => $this->news,
            'relatedNews' => $this->relatedNews,
        ]);
    }
}
