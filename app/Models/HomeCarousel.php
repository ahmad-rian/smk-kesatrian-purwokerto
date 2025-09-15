<?php

namespace App\Models;

use App\Services\ImageConversionService;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Model untuk mengelola carousel di halaman utama
 * 
 * @property string $id
 * @property string $judul
 * @property string $gambar
 * @property boolean $aktif
 * @property integer $urutan
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class HomeCarousel extends Model
{
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'aktif',
        'urutan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Mendapatkan URL gambar carousel dengan fallback
     *
     * @return string URL gambar atau placeholder jika tidak ada
     */
    public function getGambarUrlAttribute(): string
    {
        // Jika tidak ada gambar, gunakan placeholder
        if (!$this->gambar) {
            return asset('images/placeholder-image.jpg');
        }

        // Cek apakah file gambar ada di storage
        if (Storage::disk('public')->exists($this->gambar)) {
            return Storage::url($this->gambar);
        }

        // Jika file tidak ada, gunakan placeholder
        return asset('images/placeholder-image.jpg');
    }

    /**
     * Mendapatkan URL gambar WebP dengan fallback ke format asli
     *
     * @return string URL gambar WebP atau fallback
     */
    public function getGambarWebpUrlAttribute(): string
    {
        if (!$this->gambar) {
            return asset('images/placeholder-image.jpg');
        }

        // Jika sudah format WebP, return langsung
        if (str_ends_with($this->gambar, '.webp')) {
            if (Storage::disk('public')->exists($this->gambar)) {
                return Storage::url($this->gambar);
            }
        }

        // Coba cari versi WebP dari gambar asli
        $webpPath = str_replace(['.jpg', '.jpeg', '.png', '.gif'], '.webp', $this->gambar);
        if (Storage::disk('public')->exists($webpPath)) {
            return Storage::url($webpPath);
        }

        // Fallback ke gambar asli atau placeholder
        return $this->gambar_url;
    }

    /**
     * Scope untuk mendapatkan carousel yang aktif
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('aktif', true)->orderBy('urutan');
    }

    /**
     * Hapus file gambar saat model dihapus
     */
    protected static function booted()
    {
        static::deleting(function ($carousel) {
            if ($carousel->gambar) {
                $imageService = new ImageConversionService();
                $imageService->deleteOldImage($carousel->gambar);
            }
        });
    }
}
