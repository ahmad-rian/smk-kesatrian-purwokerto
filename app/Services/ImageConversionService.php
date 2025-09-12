<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service untuk konversi gambar ke format WebP
 * Mengoptimalkan performa dan mengurangi ukuran file
 */
class ImageConversionService
{
    /**
     * Kualitas kompresi WebP (0-100)
     */
    private int $quality = 85;

    /**
     * Ukuran maksimum gambar dalam pixel
     */
    private int $maxWidth = 1920;
    private int $maxHeight = 1080;

    /**
     * Konversi gambar ke format WebP
     *
     * @param UploadedFile|string $image File gambar atau path
     * @param string $directory Direktori penyimpanan
     * @param array $options Opsi tambahan (quality, maxWidth, maxHeight)
     * @return string Path file WebP yang disimpan
     */
    public function convertToWebP($image, string $directory = 'images', array $options = []): string
    {
        // Set opsi dari parameter atau gunakan default
        $quality = $options['quality'] ?? $this->quality;
        $maxWidth = $options['maxWidth'] ?? $this->maxWidth;
        $maxHeight = $options['maxHeight'] ?? $this->maxHeight;

        // Generate nama file unik dengan ekstensi .webp
        $filename = $this->generateUniqueFilename($directory);
        $fullPath = $directory . '/' . $filename;

        try {
            // Buat direktori jika belum ada
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            
            // Log untuk debugging
            Log::info('Starting image conversion', [
                'directory' => $directory,
                'filename' => $filename,
                'full_path' => $fullPath,
                'image_type' => $image instanceof UploadedFile ? 'UploadedFile' : 'string'
            ]);

            // Load gambar menggunakan GD library
            if ($image instanceof UploadedFile) {
                // Simpan file sementara untuk memastikan konten valid
                $tempPath = $image->getRealPath();
                Log::info('Using uploaded file', ['temp_path' => $tempPath, 'mime' => $image->getMimeType()]);
                
                // Deteksi tipe gambar dan buat resource yang sesuai
                $imageType = exif_imagetype($tempPath);
                switch ($imageType) {
                    case IMAGETYPE_JPEG:
                        $imageResource = imagecreatefromjpeg($tempPath);
                        break;
                    case IMAGETYPE_PNG:
                        $imageResource = imagecreatefrompng($tempPath);
                        break;
                    case IMAGETYPE_GIF:
                        $imageResource = imagecreatefromgif($tempPath);
                        break;
                    case IMAGETYPE_WEBP:
                        $imageResource = imagecreatefromwebp($tempPath);
                        break;
                    default:
                        // Fallback ke metode string jika tipe tidak dikenali
                        $imageResource = imagecreatefromstring(file_get_contents($tempPath));
                }
            } else {
                $imageResource = $this->createImageFromFile($image);
            }

            if (!$imageResource) {
                throw new \Exception('Failed to create image resource');
            }
            
            // Convert palette images to truecolor for WebP compatibility
            if (!imageistruecolor($imageResource)) {
                $trueColorImage = imagecreatetruecolor(imagesx($imageResource), imagesy($imageResource));
                
                // Preserve transparency for PNG images
                imagealphablending($trueColorImage, false);
                imagesavealpha($trueColorImage, true);
                $transparent = imagecolorallocatealpha($trueColorImage, 255, 255, 255, 127);
                imagefilledrectangle($trueColorImage, 0, 0, imagesx($imageResource), imagesy($imageResource), $transparent);
                
                imagecopy($trueColorImage, $imageResource, 0, 0, 0, 0, imagesx($imageResource), imagesy($imageResource));
                imagedestroy($imageResource);
                $imageResource = $trueColorImage;
            }

            // Get dimensi gambar
            $currentWidth = imagesx($imageResource);
            $currentHeight = imagesy($imageResource);
            
            // Resize jika melebihi ukuran maksimum dengan mempertahankan aspect ratio
            if ($currentWidth > $maxWidth || $currentHeight > $maxHeight) {
                $ratio = min($maxWidth / $currentWidth, $maxHeight / $currentHeight);
                $newWidth = (int)($currentWidth * $ratio);
                $newHeight = (int)($currentHeight * $ratio);
                
                $resizedImage = imagecreatetruecolor($newWidth, $newHeight);
                
                // Preserve transparency for resized image
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
                
                imagecopyresampled($resizedImage, $imageResource, 0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);
                imagedestroy($imageResource);
                $imageResource = $resizedImage;
            }

            // Konversi ke WebP
            ob_start();
            imagewebp($imageResource, null, $quality);
            $webpData = ob_get_contents();
            ob_end_clean();
            
            imagedestroy($imageResource);
            
            // Pastikan data WebP valid
            if (empty($webpData)) {
                throw new \Exception('Failed to generate WebP data');
            }
            
            // Simpan file WebP
            $success = Storage::disk('public')->put($fullPath, $webpData);
            
            if (!$success) {
                throw new \Exception('Failed to save WebP file to storage');
            }
            
            Log::info('Image conversion successful', [
                'path' => $fullPath,
                'size' => strlen($webpData)
            ]);
            
            // Log untuk debugging
            Log::info('Image conversion completed', [
                'full_path' => $fullPath,
                'file_exists' => Storage::disk('public')->exists($fullPath),
                'file_size' => Storage::disk('public')->size($fullPath)
            ]);

            return $fullPath;

        } catch (\Exception $e) {
            // Log error dan fallback ke penyimpanan file asli
            Log::error('Image conversion failed: ' . $e->getMessage());
            
            // Jika konversi gagal, simpan file asli
            if ($image instanceof UploadedFile) {
                $originalPath = $image->store($directory, 'public');
                return $originalPath;
            }
            
            throw $e;
        }
    }

    /**
     * Konversi multiple gambar sekaligus
     *
     * @param array $images Array dari UploadedFile
     * @param string $directory Direktori penyimpanan
     * @param array $options Opsi tambahan
     * @return array Array path file WebP yang disimpan
     */
    public function convertMultipleToWebP(array $images, string $directory = 'images', array $options = []): array
    {
        $convertedPaths = [];

        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $convertedPaths[] = $this->convertToWebP($image, $directory, $options);
            }
        }

        return $convertedPaths;
    }

    /**
     * Generate nama file unik untuk WebP
     *
     * @param string $directory
     * @return string
     */
    private function generateUniqueFilename(string $directory): string
    {
        do {
            $filename = Str::random(40) . '.webp';
            $fullPath = $directory . '/' . $filename;
        } while (Storage::disk('public')->exists($fullPath));

        return $filename;
    }

    /**
     * Hapus gambar lama jika ada
     *
     * @param string|null $oldPath
     * @return bool
     */
    public function deleteOldImage(?string $oldPath): bool
    {
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            return Storage::disk('public')->delete($oldPath);
        }

        return true;
    }

    /**
     * Validasi apakah file adalah gambar yang valid
     *
     * @param UploadedFile $file
     * @return bool
     */
    public function isValidImage(UploadedFile $file): bool
    {
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp'];
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];

        return in_array($file->getMimeType(), $allowedMimes) && 
               in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions);
    }

    /**
     * Get informasi gambar
     *
     * @param string $path
     * @return array
     */
    public function getImageInfo(string $path): array
    {
        if (!Storage::exists($path)) {
            return [];
        }

        try {
            $imageResource = $this->createImageFromFile(Storage::path($path));
            
            if (!$imageResource) {
                return [];
            }
            
            $info = [
                'width' => imagesx($imageResource),
                'height' => imagesy($imageResource),
                'size' => Storage::size($path),
                'mime' => 'image/webp',
                'url' => Storage::url($path)
            ];
            
            imagedestroy($imageResource);
            return $info;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Set kualitas kompresi
     *
     * @param int $quality
     * @return self
     */
    public function setQuality(int $quality): self
    {
        $this->quality = max(1, min(100, $quality));
        return $this;
    }

    /**
     * Set ukuran maksimum
     *
     * @param int $width
     * @param int $height
     * @return self
     */
    public function setMaxSize(int $width, int $height): self
    {
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        return $this;
    }

    /**
      * Buat resource gambar dari file path
      *
      * @param string $filePath
      * @return \GdImage|false
      */
     private function createImageFromFile(string $filePath)
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            return false;
        }

        $mimeType = $imageInfo['mime'];

        switch ($mimeType) {
            case 'image/jpeg':
                return imagecreatefromjpeg($filePath);
            case 'image/png':
                return imagecreatefrompng($filePath);
            case 'image/gif':
                return imagecreatefromgif($filePath);
            case 'image/webp':
                return imagecreatefromwebp($filePath);
            case 'image/bmp':
                return imagecreatefrombmp($filePath);
            default:
                return false;
        }
    }
}