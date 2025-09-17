<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pengumuman',
                'slug' => 'pengumuman',
                'description' => 'Pengumuman resmi sekolah untuk siswa, guru, dan orang tua',
                'color' => '#3B82F6', // Blue
                'icon' => 'o-bell',
                'sort_order' => 1
            ],
            [
                'name' => 'Prestasi',
                'slug' => 'prestasi',
                'description' => 'Berita tentang prestasi siswa dan sekolah',
                'color' => '#10B981', // Green
                'icon' => 'o-star',
                'sort_order' => 2
            ],
            [
                'name' => 'Kegiatan Sekolah',
                'slug' => 'kegiatan-sekolah',
                'description' => 'Laporan dan dokumentasi kegiatan sekolah',
                'color' => '#F59E0B', // Amber
                'icon' => 'o-folder',
                'sort_order' => 3
            ],
            [
                'name' => 'Pembelajaran',
                'slug' => 'pembelajaran',
                'description' => 'Informasi tentang kurikulum dan metode pembelajaran',
                'color' => '#8B5CF6', // Purple
                'icon' => 'o-document',
                'sort_order' => 4
            ],
            [
                'name' => 'Kemitraan',
                'slug' => 'kemitraan',
                'description' => 'Berita tentang kerjasama dengan industri dan lembaga lain',
                'color' => '#EF4444', // Red
                'icon' => 'o-heart',
                'sort_order' => 5
            ],
            [
                'name' => 'Alumni',
                'slug' => 'alumni',
                'description' => 'Berita dan kegiatan alumni sekolah',
                'color' => '#6366F1', // Indigo
                'icon' => 'o-user',
                'sort_order' => 6
            ]
        ];

        foreach ($categories as $categoryData) {
            NewsCategory::create($categoryData);
        }
    }
}
