<?php

namespace Database\Seeders;

use App\Models\FrontendMenu;
use Illuminate\Database\Seeder;

class FrontendMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing menus
        FrontendMenu::truncate();

        $menus = [
            [
                'title' => 'Beranda',
                'route_name' => 'home',
                'icon' => 'o-home',
                'sort_order' => 1,
            ],
            [
                'title' => 'Profil Sekolah',
                'route_name' => 'profil',
                'icon' => 'o-building-office-2',
                'sort_order' => 2,
            ],
            [
                'title' => 'Kegiatan',
                'route_name' => 'kegiatan',
                'icon' => 'o-calendar-days',
                'sort_order' => 3,
            ],
            [
                'title' => 'Jurusan',
                'route_name' => 'jurusan',
                'icon' => 'o-academic-cap',
                'sort_order' => 4,
            ],
            [
                'title' => 'Fasilitas',
                'route_name' => 'fasilitas.index',
                'icon' => 'o-building-library',
                'sort_order' => 5,
            ],
            [
                'title' => 'Berita',
                'route_name' => 'berita',
                'icon' => 'o-newspaper',
                'sort_order' => 6,
            ],
            [
                'title' => 'Kontak',
                'route_name' => 'kontak',
                'icon' => 'o-phone',
                'sort_order' => 7,
            ],
        ];

        foreach ($menus as $menu) {
            FrontendMenu::create($menu);
        }
    }
}
