<?php

namespace App\Console\Commands;

use App\Models\HomeCarousel;
use App\Services\ImageConversionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Command untuk mengkonversi gambar carousel yang sudah ada ke format WebP
 */
class ConvertCarouselImagesToWebP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'carousel:convert-to-webp {--force : Force conversion even if WebP already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert existing carousel images to WebP format for better performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting carousel images conversion to WebP...');

        $imageService = new ImageConversionService();
        $carousels = HomeCarousel::whereNotNull('gambar')->get();

        if ($carousels->isEmpty()) {
            $this->warn('No carousel images found to convert.');
            return 0;
        }

        $this->info("Found {$carousels->count()} carousel(s) with images.");
        $progressBar = $this->output->createProgressBar($carousels->count());
        $progressBar->start();

        $converted = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($carousels as $carousel) {
            try {
                // Skip jika sudah format WebP
                if (str_ends_with($carousel->gambar, '.webp')) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Cek apakah file asli ada
                if (!Storage::disk('public')->exists($carousel->gambar)) {
                    $this->newLine();
                    $this->warn("File not found: {$carousel->gambar} for carousel: {$carousel->judul}");
                    $errors++;
                    $progressBar->advance();
                    continue;
                }

                // Generate path WebP
                $webpPath = str_replace(['.jpg', '.jpeg', '.png', '.gif'], '.webp', $carousel->gambar);

                // Skip jika WebP sudah ada dan tidak force
                if (!$this->option('force') && Storage::disk('public')->exists($webpPath)) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Konversi ke WebP
                $originalPath = Storage::disk('public')->path($carousel->gambar);
                $newWebpPath = $imageService->convertToWebP(
                    $originalPath,
                    'carousel',
                    ['quality' => 90, 'maxWidth' => 1920, 'maxHeight' => 800]
                );

                // Update database dengan path WebP baru
                $carousel->update(['gambar' => $newWebpPath]);

                // Hapus file lama jika konversi berhasil
                if (Storage::disk('public')->exists($newWebpPath)) {
                    Storage::disk('public')->delete($carousel->gambar);
                    $converted++;
                } else {
                    $errors++;
                }
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error converting {$carousel->judul}: {$e->getMessage()}");
                $errors++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('Conversion completed!');
        $this->table(
            ['Status', 'Count'],
            [
                ['Converted', $converted],
                ['Skipped', $skipped],
                ['Errors', $errors],
                ['Total', $carousels->count()]
            ]
        );

        return $errors > 0 ? 1 : 0;
    }
}
