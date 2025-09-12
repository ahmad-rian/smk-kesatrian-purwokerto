<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use App\Services\ImageConversionService;
use Illuminate\Support\Facades\Storage;

/**
 * Model untuk Gallery Image
 * 
 * Mengelola data gambar dalam gallery dengan fitur:
 * - ULID sebagai primary key
 * - Konversi gambar otomatis ke WebP
 * - Relasi dengan Gallery
 * - Scope untuk urutan gambar
 * 
 * @property string $id
 * @property string $gallery_id
 * @property string $gambar
 * @property string|null $judul
 * @property string|null $deskripsi
 * @property int $urutan
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class GalleryImage extends Model
{
    use HasFactory, HasUlids;

    /**
     * Nama tabel database
     */
    protected $table = 'gallery_images';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'gallery_id',
        'gambar',
        'judul',
        'deskripsi',
        'urutan',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'urutan' => 'integer',
    ];

    /**
     * Mutator untuk field gambar
     * Otomatis konversi ke WebP saat upload
     */
    public function setGambarAttribute($value): void
    {
        if ($value instanceof UploadedFile) {
            // Hapus gambar lama jika ada
            if ($this->exists && $this->gambar) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($this->gambar);
            }

            // Konversi dan simpan gambar baru
            $imageService = app(ImageConversionService::class);
            $this->attributes['gambar'] = $imageService->convertToWebP(
                $value,
                'galleries/images'
            );
        } elseif (is_string($value)) {
            $this->attributes['gambar'] = $value;
        } else {
            $this->attributes['gambar'] = null;
        }
    }

    /**
     * Upload dan konversi gambar ke WebP
     */
    public function uploadGambar(UploadedFile $file): string
    {
        $imageService = app(ImageConversionService::class);
        
        // Hapus gambar lama jika ada
        if ($this->gambar) {
            $imageService->deleteOldImage($this->gambar);
        }
        
        // Konversi dan simpan gambar baru
        return $imageService->convertToWebP($file, 'galleries/images');
    }

    /**
     * Accessor untuk URL gambar
     */
    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? Storage::url($this->gambar) : null;
    }

    /**
     * Relasi dengan Gallery
     * GalleryImage milik satu Gallery
     */
    public function gallery(): BelongsTo
    {
        return $this->belongsTo(Gallery::class);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('created_at');
    }

    /**
     * Scope untuk filter berdasarkan gallery
     */
    public function scopeByGallery($query, $galleryId)
    {
        return $query->where('gallery_id', $galleryId);
    }

    /**
     * Hapus gambar dari storage
     */
    public function deleteGambar(): void
    {
        if ($this->gambar && Storage::exists($this->gambar)) {
            Storage::delete($this->gambar);
        }
    }

    /**
     * Validasi rules untuk model
     */
    public static function validationRules($id = null): array
    {
        return [
            'gallery_id' => [
                'required',
                'string',
                'exists:galleries,id',
            ],
            'gambar' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
            ],
            'judul' => [
                'nullable',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'urutan' => [
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * Get validation messages
     */
    public static function validationMessages(): array
    {
        return [
            'gallery_id.required' => 'Gallery wajib dipilih.',
            'gallery_id.exists' => 'Gallery yang dipilih tidak valid.',
            'gambar.required' => 'Gambar wajib diupload.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, GIF, WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'judul.max' => 'Judul gambar maksimal 255 karakter.',
            'deskripsi.max' => 'Deskripsi gambar maksimal 1000 karakter.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 0.',
        ];
    }

    /**
     * Get next urutan number for specific gallery
     */
    public static function getNextUrutan($galleryId): int
    {
        return static::where('gallery_id', $galleryId)->max('urutan') + 1;
    }

    /**
     * Boot method untuk event handling
     */
    protected static function boot()
    {
        parent::boot();

        // Hapus gambar saat model dihapus
        static::deleting(function ($galleryImage) {
            if ($galleryImage->gambar) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($galleryImage->gambar);
            }
        });
    }
}