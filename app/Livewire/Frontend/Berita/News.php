<?php

namespace App\Livewire\Frontend\Berita;

use App\Models\News as NewsModel;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Livewire Component untuk Halaman Berita Frontend
 * 
 * Fitur:
 * - Listing berita dengan pagination
 * - Search berita
 * - Filter berdasarkan status
 * - Responsive design
 * - SEO friendly
 */
class News extends Component
{
    use WithPagination;

    // Properties untuk filter dan search
    public $search = '';
    public $perPage = 9;

    // Reset pagination ketika search berubah
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Get berita dengan filter dan pagination
     */
    public function getNewsProperty()
    {
        return NewsModel::where('status', 'published')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('judul', 'like', '%' . $this->search . '%')
                        ->orWhere('konten', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);
    }

    /**
     * Get excerpt dari konten
     */
    public function getExcerpt($content, $limit = 150)
    {
        return strlen($content) > $limit ? substr($content, 0, $limit) . '...' : $content;
    }

    /**
     * Get formatted date
     */
    public function getFormattedDate($date)
    {
        return $date->format('d F Y');
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.frontend.berita.news', [
            'newsList' => $this->getNewsProperty()
        ]);
    }
}
