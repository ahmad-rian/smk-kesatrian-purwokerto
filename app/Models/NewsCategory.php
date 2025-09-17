<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model untuk mengelola kategori berita
 * 
 * Fitur:
 * - Manajemen kategori dengan warna dan ikon
 * - Slug otomatis dari nama
 * - Relasi ke berita
 * - Scope untuk kategori aktif
 */
class NewsCategory extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'news_categories';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'icon',
        'is_active',
        'sort_order'
    ];

    /**
     * Cast attributes ke tipe data yang sesuai
     */
    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    /**
     * Relasi ke berita
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'news_category_id');
    }

    /**
     * Relasi ke berita yang dipublikasikan
     */
    public function publishedNews(): HasMany
    {
        return $this->news()->where('status', 'published')
            ->where('tanggal_publikasi', '<=', now())
            ->orderBy('tanggal_publikasi', 'desc');
    }

    /**
     * Scope untuk kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Generate slug otomatis dari nama
     */
    public function generateSlug(): string
    {
        $slug = Str::slug($this->name);
        $count = static::where('slug', 'like', "{$slug}%")
            ->where('id', '!=', $this->id ?? 0)
            ->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Get jumlah berita dalam kategori
     */
    public function getNewsCountAttribute(): int
    {
        return $this->news()->count();
    }

    /**
     * Get jumlah berita yang dipublikasikan dalam kategori
     */
    public function getPublishedNewsCountAttribute(): int
    {
        return $this->publishedNews()->count();
    }

    /**
     * Boot model untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = $category->generateSlug();
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = $category->generateSlug();
            }
        });
    }

    /**
     * Get route key untuk model binding
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
