<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Shetabit\Visitor\Traits\Visitable;

/**
 * Model untuk mengelola berita sekolah
 * 
 * Fitur:
 * - Manajemen berita dengan kategori
 * - Upload gambar dengan mutator otomatis
 * - Status publikasi (draft/published)
 * - SEO friendly dengan slug
 */
class News extends Model
{
    use HasFactory, Visitable;

    /**
     * Nama tabel di database
     */
    protected $table = 'news';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'ringkasan',
        'gambar',
        'kategori',
        'news_category_id',
        'status',
        'tanggal_publikasi',
        'penulis',
        'tags',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views',
        'featured',
        'visitor_cookie'
    ];

    /**
     * Cast attributes ke tipe data yang sesuai
     */
    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'tags' => 'array',
        'meta_keywords' => 'array',
        'views' => 'integer',
        'featured' => 'boolean'
    ];

    /**
     * Mutator untuk menyimpan gambar
     */
    public function setGambarAttribute($value): void
    {
        if ($value && is_file($value)) {
            // Simpan file gambar ke storage
            $path = $value->store('news', 'public');
            $this->attributes['gambar'] = $path;
        } elseif (is_string($value)) {
            $this->attributes['gambar'] = $value;
        }
    }

    /**
     * Accessor untuk URL gambar lengkap
     */
    public function getGambarUrlAttribute(): ?string
    {
        return $this->gambar ? Storage::url($this->gambar) : null;
    }

    /**
     * Relasi ke kategori berita
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    /**
     * Get effective category name (from relationship or old kategori field)
     */
    public function getEffectiveCategoryAttribute(): string
    {
        return $this->category?->name ?? $this->kategori ?? 'Umum';
    }

    /**
     * Get SEO title (meta_title or fallback to judul)
     */
    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->judul;
    }

    /**
     * Get SEO description (meta_description or fallback to ringkasan)
     */
    public function getSeoDescriptionAttribute(): string
    {
        return $this->meta_description ?: ($this->ringkasan ? strip_tags($this->ringkasan) : '');
    }

    /**
     * Scope untuk berita yang dipublikasikan
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('tanggal_publikasi', '<=', now());
    }

    /**
     * Scope untuk berita berdasarkan kategori
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('kategori', $category);
    }

    /**
     * Scope untuk berita terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_publikasi', 'desc');
    }

    /**
     * Scope untuk berita featured
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope untuk berita berdasarkan kategori (ID atau slug)
     */
    public function scopeByCategoryId($query, $categoryId)
    {
        return $query->where('news_category_id', $categoryId);
    }

    /**
     * Scope untuk pencarian berita
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('judul', 'like', "%{$search}%")
                ->orWhere('konten', 'like', "%{$search}%")
                ->orWhere('ringkasan', 'like', "%{$search}%")
                ->orWhere('penulis', 'like', "%{$search}%");
        });
    }

    /**
     * Generate slug otomatis dari judul
     */
    public function generateSlug(): string
    {
        $slug = Str::slug($this->judul);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Get visitor count for this news
     */
    public function getVisitorCount(): int
    {
        // Use both shetabit visitor and custom views field
        $shetabitCount = \Shetabit\Visitor\Models\Visit::where('visitable_type', self::class)
            ->where('visitable_id', $this->id)
            ->count();

        // Return max between shetabit count and views field
        return max($shetabitCount, $this->views ?? 0);
    }

    /**
     * Increment visitor count
     */
    public function incrementVisitorCount(): void
    {
        $this->increment('views');
    }

    /**
     * Get unique visitor count for this news
     */
    public function getUniqueVisitorCount(): int
    {
        return \Shetabit\Visitor\Models\Visit::where('visitable_type', self::class)
            ->where('visitable_id', $this->id)
            ->distinct('ip')
            ->count();
    }

    /**
     * Boot model untuk auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = $news->generateSlug();
            }

            if (empty($news->tanggal_publikasi)) {
                $news->tanggal_publikasi = now();
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
