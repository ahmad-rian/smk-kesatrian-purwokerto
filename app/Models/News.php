<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
    use HasFactory;

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
        'status',
        'tanggal_publikasi',
        'penulis',
        'tags',
        'views'
    ];

    /**
     * Cast attributes ke tipe data yang sesuai
     */
    protected $casts = [
        'tanggal_publikasi' => 'datetime',
        'tags' => 'array',
        'views' => 'integer'
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
     * Generate slug otomatis dari judul
     */
    public function generateSlug(): string
    {
        $slug = Str::slug($this->judul);
        $count = static::where('slug', 'like', "{$slug}%")->count();
        
        return $count > 0 ? "{$slug}-{$count}" : $slug;
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