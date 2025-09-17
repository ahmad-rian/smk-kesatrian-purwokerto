<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\News;
use App\Services\NewsVisitorService;

/**
 * Controller untuk halaman berita frontend
 * Menggunakan layout dan memanggil Livewire component
 */
class NewsController extends Controller
{
    protected NewsVisitorService $visitorService;

    public function __construct(NewsVisitorService $visitorService)
    {
        $this->visitorService = $visitorService;
    }

    /**
     * Tampilkan halaman daftar berita
     */
    public function index()
    {
        return view('pages.news');
    }

    /**
     * Tampilkan detail berita berdasarkan slug
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->where('status', 'published')
            ->where('tanggal_publikasi', '<=', now())
            ->with(['category'])
            ->firstOrFail();

        // Track visitor untuk berita ini dengan sistem baru
        $this->visitorService->trackVisitor($news);

        // Track juga dengan visitor package untuk backward compatibility
        visitor($news)->visit();

        // Increment views count (simple counter)
        $news->incrementVisitorCount();

        // SEO Meta untuk detail berita
        $title = $news->meta_title ?: $news->judul;
        $description = $news->meta_description ?: $news->ringkasan;
        $keywords = $news->meta_keywords ?: '';
        $image = $news->gambar ? Storage::url($news->gambar) : '';

        return view('pages.news-detail', compact('news', 'slug', 'title', 'description', 'keywords', 'image'));
    }
}
