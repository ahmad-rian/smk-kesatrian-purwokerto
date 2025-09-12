<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use App\Services\ImageConversionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Model untuk Gallery
 * 
 * Mengelola data gallery dengan fitur:
 * - ULID sebagai primary key
 * - Auto-generate slug dari judul
 * - Konversi gambar otomatis ke WebP
 * - Relasi dengan GalleryImage
 * - Scope untuk gallery aktif dan urutan
 * 
 * @property string $id
 * @property string $judul
 * @property string $slug
 * @property string|null $deskripsi
 * @property string|null $gambar_sampul
 * @property bool $aktif
 * @property int $urutan
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class Gallery extends Model
{
    use HasFactory, HasUlids;

    /**
     * Nama tabel database
     */
    protected $table = 'galleries';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'gambar_sampul',
        'aktif',
        'urutan',
        'tanggal_kegiatan',
        'dibuat_oleh',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Boot method untuk auto-generate slug dan event handling
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->judul);
            }
        });

        static::updating(function ($gallery) {
            if ($gallery->isDirty('judul') && empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->judul);
            }
        });

        // Hapus gambar sampul dan semua gambar gallery saat model dihapus
        static::deleting(function ($gallery) {
            // Hapus gambar sampul
            if ($gallery->gambar_sampul) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($gallery->gambar_sampul);
            }

            // Hapus semua gambar dalam gallery
            foreach ($gallery->images as $image) {
                if ($image->gambar) {
                    $imageService = app(ImageConversionService::class);
                    $imageService->deleteOldImage($image->gambar);
                }
            }
        });
    }

    /**
     * Mutator untuk field gambar_sampul
     * Otomatis konversi ke WebP saat upload
     */
    public function setGambarSampulAttribute($value): void
    {
        if ($value instanceof UploadedFile) {
            // Hapus gambar lama jika ada
            if ($this->exists && $this->gambar_sampul) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($this->gambar_sampul);
            }

            // Konversi dan simpan gambar baru
            $imageService = app(ImageConversionService::class);
            $this->attributes['gambar_sampul'] = $imageService->convertToWebP(
                $value,
                'galleries/sampul'
            );
        } elseif (is_string($value)) {
            $this->attributes['gambar_sampul'] = $value;
        } else {
            $this->attributes['gambar_sampul'] = null;
        }
    }

    /**
     * Upload dan konversi gambar sampul ke WebP
     */
    public function uploadGambarSampul(UploadedFile $file): string
    {
        $imageService = app(ImageConversionService::class);
        
        // Hapus gambar lama jika ada
        if ($this->gambar_sampul) {
            $imageService->deleteOldImage($this->gambar_sampul);
        }
        
        // Konversi dan simpan gambar baru
        return $imageService->convertToWebP($file, 'galleries/sampul');
    }

    /**
     * Accessor untuk URL gambar sampul
     */
    public function getGambarSampulUrlAttribute(): ?string
    {
        return $this->gambar_sampul ? Storage::url($this->gambar_sampul) : null;
    }

    /**
     * Relasi dengan GalleryImage
     * Gallery memiliki banyak gambar
     */
    public function images(): HasMany
    {
        return $this->hasMany(GalleryImage::class)->orderBy('urutan');
    }

    /**
     * Scope untuk gallery aktif
     */
    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('judul');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('slug', 'like', "%{$search}%");
        });
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        if ($status === 'aktif') {
            return $query->where('aktif', true);
        } elseif ($status === 'nonaktif') {
            return $query->where('aktif', false);
        }
        return $query;
    }

    /**
     * Hapus gambar sampul dari storage
     */
    public function deleteGambarSampul(): void
    {
        if ($this->gambar_sampul && Storage::exists($this->gambar_sampul)) {
            Storage::delete($this->gambar_sampul);
        }
    }

    /**
     * Validasi rules untuk model
     */
    public static function validationRules($id = null): array
    {
        return [
            'judul' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9-]+$/',
                'unique:galleries,slug' . ($id ? ",{$id}" : ''),
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'gambar_sampul' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
            ],
            'aktif' => [
                'boolean',
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
            'judul.required' => 'Judul gallery wajib diisi.',
            'judul.max' => 'Judul gallery maksimal 255 karakter.',
            'slug.unique' => 'Slug sudah digunakan, silakan gunakan yang lain.',
            'slug.regex' => 'Slug hanya boleh berisi huruf kecil, angka, dan tanda hubung.',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter.',
            'gambar_sampul.image' => 'File harus berupa gambar.',
            'gambar_sampul.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, GIF, WebP.',
            'gambar_sampul.max' => 'Ukuran gambar maksimal 2MB.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 0.',
        ];
    }

    /**
     * Get next urutan number
     */
    public static function getNextUrutan(): int
    {
        return static::max('urutan') + 1;
    }

    /**
     * Get total images count
     */
    public function getTotalImagesAttribute(): int
    {
        return $this->images()->count();
    }


}