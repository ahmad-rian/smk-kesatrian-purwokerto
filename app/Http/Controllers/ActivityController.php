<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller untuk menangani halaman kegiatan sekolah
 * 
 * Menggunakan pattern yang sama dengan NewsController
 * untuk konsistensi dalam handling view dan layout
 */
class ActivityController extends Controller
{
    /**
     * Menampilkan halaman daftar kegiatan
     * 
     * @return View
     */
    public function index(): View
    {
        return view('pages.activities');
    }

    /**
     * Menampilkan detail kegiatan berdasarkan ID
     * 
     * @param string $id ULID dari kegiatan
     * @return View
     */
    public function show(string $id): View
    {
        return view('pages.activity-detail', compact('id'));
    }
}