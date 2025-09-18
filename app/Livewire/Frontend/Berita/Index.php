<?php

namespace App\Livewire\Frontend\Berita;

use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

/**
 * Komponen Frontend untuk listing berita dengan filter kategori
 */
#[Layout('components.layouts.frontend')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'kategori')]
    public ?string $selectedCategory = null;

    #[Url(as: 'cari')]
    public string $search = '';

    public int $perPage = 12;

    /**
     * Reset pagination when filters change
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory(): void
    {
        $this->resetPage();
    }

    /**
     * Filter by category
     */
    public function filterByCategory(?string $categorySlug): void
    {
        $this->selectedCategory = $categorySlug;
        $this->resetPage();
    }

    /**
     * Clear all filters
     */
    public function clearFilters(): void
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Refresh news data to get latest view counts
     */
    public function refreshNews(): void
    {
        // Force re-render to get fresh data
        $this->dispatch('$refresh');
    }

    /**
     * Get filtered news
     */
    public function getNewsProperty()
    {
        $query = News::query()
            ->with(['category'])
            ->published()
            ->latest('tanggal_publikasi');

        // Apply category filter
        if ($this->selectedCategory) {
            $category = NewsCategory::where('slug', $this->selectedCategory)->first();
            if ($category) {
                $query->where('news_category_id', $category->id);
            }
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('ringkasan', 'like', '%' . $this->search . '%')
                    ->orWhere('konten', 'like', '%' . $this->search . '%');
            });
        }

        // Force fresh data from database to get latest view counts
        return $query->paginate($this->perPage);
    }

    /**
     * Get available categories
     */
    public function getCategoriesProperty()
    {
        return NewsCategory::active()
            ->withCount(['publishedNews'])
            ->having('published_news_count', '>', 0)
            ->ordered()
            ->get();
    }

    /**
     * Get selected category object
     */
    public function getSelectedCategoryObjectProperty()
    {
        if (!$this->selectedCategory) {
            return null;
        }

        return NewsCategory::where('slug', $this->selectedCategory)->first();
    }

    /**
     * Get news count for current filters
     */
    public function getNewsCountProperty(): int
    {
        $query = News::query()->published();

        if ($this->selectedCategory) {
            $category = NewsCategory::where('slug', $this->selectedCategory)->first();
            if ($category) {
                $query->where('news_category_id', $category->id);
            }
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('judul', 'like', '%' . $this->search . '%')
                    ->orWhere('ringkasan', 'like', '%' . $this->search . '%')
                    ->orWhere('konten', 'like', '%' . $this->search . '%');
            });
        }

        return $query->count();
    }

    /**
     * Format date for display
     */
    public function getFormattedDate($date): string
    {
        return $date->format('d M Y');
    }

    /**
     * Get excerpt from content
     */
    public function getExcerpt(string $content, int $limit = 150): string
    {
        return Str::limit(strip_tags($content), $limit);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.frontend.berita.news', [
            'news' => $this->news,
            'categories' => $this->categories,
            'selectedCategoryObject' => $this->selectedCategoryObject,
            'newsCount' => $this->newsCount
        ]);
    }
}
