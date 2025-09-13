<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Controller untuk halaman berita frontend
 * Menggunakan layout dan memanggil Livewire component
 */
class NewsController extends Controller
{
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
        return view('pages.news-detail', compact('slug'));
    }
}