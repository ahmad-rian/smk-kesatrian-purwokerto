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
        
        // Bersihkan direktori storage
        if (Storage::disk('public')->exists('galleries')) {
            Storage::disk('public')->deleteDirectory('galleries');
        }
        
        // Buat direktori storage baru
        Storage::disk('public')->makeDirectory('galleries');

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

        foreach ($galleries as $galleryData) {
            // Generate slug dari judul
            $galleryData['slug'] = Str::slug($galleryData['judul']);
            
            // Create placeholder gambar sampul
            $sampulFilename = 'galleries/sampul-' . $galleryData['slug'] . '.webp';
            $this->createPlaceholderImage($sampulFilename, 800, 600, $galleryData['judul']);
            $galleryData['gambar_sampul'] = $sampulFilename;

            // Buat gallery
            $gallery = Gallery::create($galleryData);

            // Buat sample images untuk setiap gallery
            $imageCount = rand(3, 8); // Random 3-8 gambar per gallery
            
            for ($i = 1; $i <= $imageCount; $i++) {
                $imageFilename = "galleries/{$gallery->slug}/image-{$i}.webp";
                $this->createPlaceholderImage($imageFilename, 600, 400, "Gambar {$i}");
                
                GalleryImage::create([
                    'id' => Str::ulid(),
                    'gallery_id' => $gallery->id,
                    'gambar' => $imageFilename,
                    'urutan' => $i,
                ]);
            }
        }

        $this->command->info('Gallery seeder completed successfully!');
        $this->command->info('Created ' . Gallery::count() . ' galleries with ' . GalleryImage::count() . ' images.');
    }

    /**
     * Create placeholder image untuk testing
     */
    private function createPlaceholderImage(string $filename, int $width, int $height, string $text): void
    {
        // Pastikan direktori ada
        $directory = dirname($filename);
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        // Create simple SVG placeholder
        $svg = $this->generatePlaceholderSVG($width, $height, $text);
        
        // Save as WebP-like file (actually SVG for simplicity)
        Storage::disk('public')->put($filename, $svg);
    }

    /**
     * Generate SVG placeholder image
     */
    private function generatePlaceholderSVG(int $width, int $height, string $text): string
    {
        $colors = [
            '#3B82F6', // Blue
            '#10B981', // Green
            '#F59E0B', // Yellow
            '#EF4444', // Red
            '#8B5CF6', // Purple
            '#06B6D4', // Cyan
            '#F97316', // Orange
            '#84CC16', // Lime
        ];
        
        $bgColor = $colors[array_rand($colors)];
        $textColor = '#FFFFFF';
        
        return <<<SVG
<?xml version="1.0" encoding="UTF-8"?>
<svg width="{$width}" height="{$height}" xmlns="http://www.w3.org/2000/svg">
    <rect width="100%" height="100%" fill="{$bgColor}"/>
    <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="24" font-weight="bold" 
          text-anchor="middle" dominant-baseline="middle" fill="{$textColor}">
        {$text}
    </text>
    <text x="50%" y="70%" font-family="Arial, sans-serif" font-size="14" 
          text-anchor="middle" dominant-baseline="middle" fill="{$textColor}" opacity="0.8">
        {$width} × {$height}
    </text>
</svg>
SVG;
    }

    /**
     * Clean up method untuk menghapus file placeholder saat rollback
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