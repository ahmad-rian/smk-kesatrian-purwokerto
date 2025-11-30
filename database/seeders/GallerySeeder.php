<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Seeder untuk Gallery dan GalleryImage
 * 
 * Membuat data sample gallery dengan gambar untuk testing
 * dan demonstrasi fungsionalitas sistem gallery
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus data lama terlebih dahulu
        GalleryImage::query()->delete();
        Gallery::query()->delete();

        // Data sample galleries
        $galleries = [
            [
                'id' => Str::ulid(),
                'judul' => 'Kegiatan Pembelajaran',
                'deskripsi' => 'Dokumentasi kegiatan pembelajaran siswa di berbagai jurusan dan program studi.',
                'aktif' => true,
                'tanggal_kegiatan' => now()->subDays(30)->toDateString(),
                'urutan' => 1,
                'dibuat_oleh' => null,
            ],
            [
                'id' => Str::ulid(),
                'judul' => 'Fasilitas Sekolah',
                'deskripsi' => 'Fasilitas lengkap dan modern yang mendukung proses pembelajaran di SMK Kesatrian.',
                'aktif' => true,
                'tanggal_kegiatan' => now()->subDays(20)->toDateString(),
                'urutan' => 2,
                'dibuat_oleh' => null,
            ],
            [
                'id' => Str::ulid(),
                'judul' => 'Kegiatan Ekstrakurikuler',
                'deskripsi' => 'Berbagai kegiatan ekstrakurikuler yang mengembangkan bakat dan minat siswa.',
                'aktif' => true,
                'tanggal_kegiatan' => now()->subDays(15)->toDateString(),
                'urutan' => 3,
                'dibuat_oleh' => null,
            ],
            [
                'id' => Str::ulid(),
                'judul' => 'Acara Sekolah',
                'deskripsi' => 'Dokumentasi berbagai acara dan event yang diselenggarakan sekolah.',
                'aktif' => true,
                'tanggal_kegiatan' => now()->subDays(10)->toDateString(),
                'urutan' => 4,
                'dibuat_oleh' => null,
            ],
            [
                'id' => Str::ulid(),
                'judul' => 'Prestasi Siswa',
                'deskripsi' => 'Dokumentasi prestasi dan penghargaan yang diraih siswa SMK Kesatrian.',
                'aktif' => false, // Draft gallery
                'tanggal_kegiatan' => now()->subDays(5)->toDateString(),
                'urutan' => 5,
                'dibuat_oleh' => null,
            ],
        ];

        foreach ($galleries as $index => $galleryData) {
            // Generate slug dari judul
            $galleryData['slug'] = Str::slug($galleryData['judul']);

            // Assign Picsum Photos images for cover
            $coverImages = [
                'https://picsum.photos/seed/gallery1/1200/800', // Kegiatan Pembelajaran
                'https://picsum.photos/seed/gallery2/1200/800', // Fasilitas Sekolah
                'https://picsum.photos/seed/gallery3/1200/800', // Ekstrakurikuler
                'https://picsum.photos/seed/gallery4/1200/800', // Acara Sekolah
                'https://picsum.photos/seed/gallery5/1200/800', // Prestasi Siswa
            ];
            $galleryData['gambar_sampul'] = $coverImages[$index];

            // Buat gallery
            $gallery = Gallery::create($galleryData);

            // Define image sets for each gallery using Picsum Photos
            $imageSets = [
                // Kegiatan Pembelajaran
                [
                    'https://picsum.photos/seed/gallery1-img1/800/600',
                    'https://picsum.photos/seed/gallery1-img2/800/600',
                    'https://picsum.photos/seed/gallery1-img3/800/600',
                    'https://picsum.photos/seed/gallery1-img4/800/600',
                    'https://picsum.photos/seed/gallery1-img5/800/600',
                ],
                // Fasilitas Sekolah
                [
                    'https://picsum.photos/seed/gallery2-img1/800/600',
                    'https://picsum.photos/seed/gallery2-img2/800/600',
                    'https://picsum.photos/seed/gallery2-img3/800/600',
                    'https://picsum.photos/seed/gallery2-img4/800/600',
                    'https://picsum.photos/seed/gallery2-img5/800/600',
                    'https://picsum.photos/seed/gallery2-img6/800/600',
                ],
                // Kegiatan Ekstrakurikuler
                [
                    'https://picsum.photos/seed/gallery3-img1/800/600',
                    'https://picsum.photos/seed/gallery3-img2/800/600',
                    'https://picsum.photos/seed/gallery3-img3/800/600',
                    'https://picsum.photos/seed/gallery3-img4/800/600',
                ],
                // Acara Sekolah
                [
                    'https://picsum.photos/seed/gallery4-img1/800/600',
                    'https://picsum.photos/seed/gallery4-img2/800/600',
                    'https://picsum.photos/seed/gallery4-img3/800/600',
                    'https://picsum.photos/seed/gallery4-img4/800/600',
                    'https://picsum.photos/seed/gallery4-img5/800/600',
                ],
                // Prestasi Siswa
                [
                    'https://picsum.photos/seed/gallery5-img1/800/600',
                    'https://picsum.photos/seed/gallery5-img2/800/600',
                    'https://picsum.photos/seed/gallery5-img3/800/600',
                    'https://picsum.photos/seed/gallery5-img4/800/600',
                    'https://picsum.photos/seed/gallery5-img5/800/600',
                    'https://picsum.photos/seed/gallery5-img6/800/600',
                ],
            ];

            // Get images for this gallery
            $galleryImages = $imageSets[$index];

            foreach ($galleryImages as $i => $imageUrl) {
                GalleryImage::create([
                    'id' => Str::ulid(),
                    'gallery_id' => $gallery->id,
                    'gambar' => $imageUrl,
                    'urutan' => $i + 1,
                ]);
            }
        }

        $this->command->info('Gallery seeder completed successfully!');
        $this->command->info('Created ' . Gallery::count() . ' galleries with ' . GalleryImage::count() . ' images.');
    }

    /**
     * Clean up method untuk menghapus data saat rollback
     */
    public function cleanup(): void
    {
        // Hapus semua file di direktori galleries
        if (Storage::disk('public')->exists('galleries')) {
            Storage::disk('public')->deleteDirectory('galleries');
        }

        // Hapus data dari database
        GalleryImage::truncate();
        Gallery::truncate();

        $this->command->info('Gallery seeder cleanup completed.');
    }
}