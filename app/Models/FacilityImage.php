<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Services\ImageConversionService;

/**
 * Model untuk Gambar Fasilitas
 * 
 * Mengelola multiple gambar untuk setiap fasilitas dengan fitur:
 * - ULID sebagai primary key
 * - Konversi gambar otomatis ke WebP
 * - Relasi dengan Facility
 * - Urutan tampil gambar
 * - Gambar utama (primary)
 * 
 * @property string $id
 * @property string $facility_id
 * @property string $gambar
 * @property string|null $alt_text
 * @property int $urutan
 * @property bool $is_primary
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class FacilityImage extends Model
{
    use HasFactory, HasUlids;

    /**
     * Nama tabel database
     */
    protected $table = 'facility_images';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'facility_id',
        'gambar',
        'alt_text',
        'urutan',
        'is_primary',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'is_primary' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Relasi ke Facility
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    /**
     * Scope untuk gambar utama
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }

    /**
     * Scope untuk urutan gambar
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('created_at');
    }

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
                'facilities/images'
            );
        } elseif (is_string($value)) {
            $this->attributes['gambar'] = $value;
        } else {
            $this->attributes['gambar'] = null;
        }
    }

    /**
     * Accessor untuk URL gambar
     */
    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? Storage::url($this->gambar) : null;
    }

    /**
     * Upload dan konversi gambar ke WebP
     */
    public function uploadImage(UploadedFile $file): string
    {
        $imageService = app(ImageConversionService::class);

        // Hapus gambar lama jika ada
        if ($this->gambar) {
            $imageService->deleteOldImage($this->gambar);
        }

        // Konversi dan simpan gambar baru
        return $imageService->convertToWebP($file, 'facilities/images');
    }

    /**
     * Set sebagai gambar utama
     * Otomatis unset gambar utama lainnya dalam fasilitas yang sama
     */
    public function setPrimary(): void
    {
        // Unset semua gambar utama lainnya dalam fasilitas ini
        static::where('facility_id', $this->facility_id)
            ->where('id', '!=', $this->id)
            ->update(['is_primary' => false]);

        // Set gambar ini sebagai utama
        $this->update(['is_primary' => true]);
    }

    /**
     * Boot model events
     */
    protected static function boot()
    {
        parent::boot();

        // Hapus file gambar saat model dihapus
        static::deleting(function ($facilityImage) {
            if ($facilityImage->gambar) {
                Storage::disk('public')->delete($facilityImage->gambar);
            }
        });

        // Auto set sebagai primary jika ini gambar pertama
        static::created(function ($facilityImage) {
            $count = static::where('facility_id', $facilityImage->facility_id)->count();
            if ($count === 1) {
                $facilityImage->update(['is_primary' => true]);
            }
        });
    }
}
