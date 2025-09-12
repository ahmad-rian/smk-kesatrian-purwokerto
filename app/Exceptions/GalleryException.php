<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

/**
 * Custom Exception untuk operasi Gallery
 * 
 * Menangani berbagai jenis error yang terjadi pada operasi Gallery
 * dengan pesan error yang user-friendly dan logging yang tepat
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class GalleryException extends Exception
{
    /**
     * Error codes untuk berbagai jenis error
     */
    public const GALLERY_NOT_FOUND = 'GALLERY_NOT_FOUND';
    public const GALLERY_IMAGE_NOT_FOUND = 'GALLERY_IMAGE_NOT_FOUND';
    public const INVALID_IMAGE_FORMAT = 'INVALID_IMAGE_FORMAT';
    public const IMAGE_UPLOAD_FAILED = 'IMAGE_UPLOAD_FAILED';
    public const IMAGE_CONVERSION_FAILED = 'IMAGE_CONVERSION_FAILED';
    public const IMAGE_DELETE_FAILED = 'IMAGE_DELETE_FAILED';
    public const GALLERY_SAVE_FAILED = 'GALLERY_SAVE_FAILED';
    public const GALLERY_DELETE_FAILED = 'GALLERY_DELETE_FAILED';
    public const SLUG_GENERATION_FAILED = 'SLUG_GENERATION_FAILED';
    public const PERMISSION_DENIED = 'PERMISSION_DENIED';
    public const INVALID_GALLERY_DATA = 'INVALID_GALLERY_DATA';
    public const MAX_IMAGES_EXCEEDED = 'MAX_IMAGES_EXCEEDED';

    /**
     * Error code untuk exception ini
     */
    protected string $errorCode;

    /**
     * Data tambahan untuk context
     */
    protected array $context;

    /**
     * Constructor
     */
    public function __construct(
        string $message = '',
        string $errorCode = '',
        array $context = [],
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->errorCode = $errorCode;
        $this->context = $context;
    }

    /**
     * Get error code
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    /**
     * Get context data
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Static factory methods untuk berbagai jenis error
     */
    public static function galleryNotFound(string $galleryId = ''): self
    {
        return new self(
            'Gallery tidak ditemukan.',
            self::GALLERY_NOT_FOUND,
            ['gallery_id' => $galleryId],
            404
        );
    }

    public static function galleryImageNotFound(string $imageId = ''): self
    {
        return new self(
            'Gambar gallery tidak ditemukan.',
            self::GALLERY_IMAGE_NOT_FOUND,
            ['image_id' => $imageId],
            404
        );
    }

    public static function invalidImageFormat(string $filename = ''): self
    {
        return new self(
            'Format gambar tidak valid. Gunakan format JPEG, PNG, atau WebP.',
            self::INVALID_IMAGE_FORMAT,
            ['filename' => $filename],
            422
        );
    }

    public static function imageUploadFailed(string $reason = ''): self
    {
        return new self(
            'Gagal mengupload gambar. ' . $reason,
            self::IMAGE_UPLOAD_FAILED,
            ['reason' => $reason],
            500
        );
    }

    public static function imageConversionFailed(string $filename = '', string $reason = ''): self
    {
        return new self(
            'Gagal mengkonversi gambar ke format WebP.',
            self::IMAGE_CONVERSION_FAILED,
            ['filename' => $filename, 'reason' => $reason],
            500
        );
    }

    public static function imageDeleteFailed(string $filename = '', string $reason = ''): self
    {
        return new self(
            'Gagal menghapus gambar dari storage.',
            self::IMAGE_DELETE_FAILED,
            ['filename' => $filename, 'reason' => $reason],
            500
        );
    }

    public static function gallerySaveFailed(string $reason = ''): self
    {
        return new self(
            'Gagal menyimpan data gallery.',
            self::GALLERY_SAVE_FAILED,
            ['reason' => $reason],
            500
        );
    }

    public static function galleryDeleteFailed(string $galleryId = '', string $reason = ''): self
    {
        return new self(
            'Gagal menghapus gallery.',
            self::GALLERY_DELETE_FAILED,
            ['gallery_id' => $galleryId, 'reason' => $reason],
            500
        );
    }

    public static function slugGenerationFailed(string $title = ''): self
    {
        return new self(
            'Gagal membuat slug dari judul gallery.',
            self::SLUG_GENERATION_FAILED,
            ['title' => $title],
            500
        );
    }

    public static function permissionDenied(string $action = ''): self
    {
        return new self(
            'Anda tidak memiliki izin untuk melakukan aksi ini.',
            self::PERMISSION_DENIED,
            ['action' => $action],
            403
        );
    }

    public static function invalidGalleryData(array $errors = []): self
    {
        return new self(
            'Data gallery tidak valid.',
            self::INVALID_GALLERY_DATA,
            ['validation_errors' => $errors],
            422
        );
    }

    public static function maxImagesExceeded(int $maxImages = 50): self
    {
        return new self(
            "Maksimal {$maxImages} gambar dapat ditambahkan ke gallery.",
            self::MAX_IMAGES_EXCEEDED,
            ['max_images' => $maxImages],
            422
        );
    }

    /**
     * Render exception untuk HTTP response
     */
    public function render(Request $request)
    {
        // Log error dengan context
        Log::error('Gallery Exception: ' . $this->getMessage(), [
            'error_code' => $this->errorCode,
            'context' => $this->context,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'trace' => $this->getTraceAsString(),
        ]);

        // Return JSON response untuk AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'error_code' => $this->errorCode,
                'errors' => $this->context['validation_errors'] ?? null,
            ], $this->getCode() ?: 500);
        }

        // Return redirect dengan error message untuk web requests
        return redirect()->back()
            ->withErrors(['gallery' => $this->getMessage()])
            ->withInput();
    }

    /**
     * Get user-friendly error message berdasarkan error code
     */
    public function getUserFriendlyMessage(): string
    {
        return match ($this->errorCode) {
            self::GALLERY_NOT_FOUND => 'Gallery yang Anda cari tidak ditemukan.',
            self::GALLERY_IMAGE_NOT_FOUND => 'Gambar yang Anda cari tidak ditemukan.',
            self::INVALID_IMAGE_FORMAT => 'Format gambar tidak didukung. Silakan gunakan JPEG, PNG, atau WebP.',
            self::IMAGE_UPLOAD_FAILED => 'Terjadi kesalahan saat mengupload gambar. Silakan coba lagi.',
            self::IMAGE_CONVERSION_FAILED => 'Gagal memproses gambar. Silakan coba dengan gambar lain.',
            self::IMAGE_DELETE_FAILED => 'Gagal menghapus gambar. Silakan coba lagi.',
            self::GALLERY_SAVE_FAILED => 'Gagal menyimpan gallery. Silakan periksa data dan coba lagi.',
            self::GALLERY_DELETE_FAILED => 'Gagal menghapus gallery. Silakan coba lagi.',
            self::SLUG_GENERATION_FAILED => 'Gagal membuat URL gallery. Silakan gunakan judul yang berbeda.',
            self::PERMISSION_DENIED => 'Anda tidak memiliki izin untuk melakukan aksi ini.',
            self::INVALID_GALLERY_DATA => 'Data gallery tidak valid. Silakan periksa form dan coba lagi.',
            self::MAX_IMAGES_EXCEEDED => 'Jumlah gambar melebihi batas maksimal yang diizinkan.',
            default => $this->getMessage() ?: 'Terjadi kesalahan yang tidak diketahui.',
        };
    }

    /**
     * Check if this is a client error (4xx)
     */
    public function isClientError(): bool
    {
        return $this->getCode() >= 400 && $this->getCode() < 500;
    }

    /**
     * Check if this is a server error (5xx)
     */
    public function isServerError(): bool
    {
        return $this->getCode() >= 500;
    }

    /**
     * Convert to array untuk logging atau debugging
     */
    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'error_code' => $this->errorCode,
            'context' => $this->context,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
            'code' => $this->getCode(),
        ];
    }
}