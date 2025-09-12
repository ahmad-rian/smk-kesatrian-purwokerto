<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use App\Services\ImageConversionService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Model untuk Kegiatan Sekolah
 * 
 * Mengelola data kegiatan sekolah dengan fitur:
 * - ULID sebagai primary key
 * - Auto-generate slug dari nama kegiatan
 * - Konversi gambar otomatis ke WebP
 * - Relasi dengan User (pembuat)
 * - Scope untuk kegiatan aktif dan unggulan
 */
class SchoolActivity extends Model
{
    use HasFactory, HasUlids;

    /**
     * Nama tabel database
     */
    protected $table = 'school_activities';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'nama_kegiatan',
        'slug',
        'deskripsi',
        'gambar_utama',
        'kategori',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'penanggungjawab',
        'aktif',
        'unggulan',
        'dibuat_oleh',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'aktif' => 'boolean',
        'unggulan' => 'boolean',
    ];

    /**
     * Mutator untuk field nama_kegiatan
     * Auto-generate slug saat nama kegiatan diubah
     */
    public function setNamaKegiatanAttribute($value): void
    {
        $this->attributes['nama_kegiatan'] = $value;
        
        // Auto-generate slug jika belum ada atau nama berubah
        if (!$this->slug || $this->isDirty('nama_kegiatan')) {
            $this->attributes['slug'] = $this->generateUniqueSlug($value);
        }
    }

    /**
     * Mutator untuk field gambar_utama
     * Otomatis konversi ke WebP saat upload
     */
    public function setGambarUtamaAttribute($value): void
    {
        if ($value instanceof UploadedFile) {
            // Hapus gambar lama jika ada
            if ($this->exists && $this->gambar_utama) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($this->gambar_utama);
            }

            // Konversi dan simpan gambar baru
            $imageService = app(ImageConversionService::class);
            $this->attributes['gambar_utama'] = $imageService->convertToWebP(
                $value,
                'school-activities/images'
            );
        } elseif (is_string($value)) {
            $this->attributes['gambar_utama'] = $value;
        } else {
            $this->attributes['gambar_utama'] = null;
        }
    }

    /**
     * Accessor untuk URL gambar utama
     */
    public function getGambarUtamaUrlAttribute(): ?string
    {
        return $this->gambar_utama ? Storage::url($this->gambar_utama) : null;
    }

    /**
     * Accessor untuk status kegiatan berdasarkan tanggal
     */
    public function getStatusKegiatanAttribute(): string
    {
        $today = now()->toDateString();
        
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            if ($today < $this->tanggal_mulai->toDateString()) {
                return 'akan_datang';
            } elseif ($today > $this->tanggal_selesai->toDateString()) {
                return 'selesai';
            } else {
                return 'berlangsung';
            }
        }
        
        return 'tidak_terjadwal';
    }

    /**
     * Relasi dengan User (pembuat)
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Scope untuk kegiatan aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk kegiatan unggulan
     */
    public function scopeUnggulan($query)
    {
        return $query->where('unggulan', true);
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('nama_kegiatan', 'like', "%{$search}%")
              ->orWhere('deskripsi', 'like', "%{$search}%")
              ->orWhere('kategori', 'like', "%{$search}%")
              ->orWhere('lokasi', 'like', "%{$search}%")
              ->orWhere('penanggungjawab', 'like', "%{$search}%");
        });
    }

    /**
     * Scope untuk mengurutkan berdasarkan tanggal terbaru
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('tanggal_mulai', 'desc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Generate unique slug
     */
    private function generateUniqueSlug($nama): string
    {
        $slug = Str::slug($nama);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? '')->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Boot method untuk event handling
     */
    protected static function boot()
    {
        parent::boot();

        // Hapus gambar saat model dihapus
        static::deleting(function ($schoolActivity) {
            if ($schoolActivity->gambar_utama) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($schoolActivity->gambar_utama);
            }
        });
    }

    /**
     * Validasi rules untuk model
     */
    public static function validationRules($id = null): array
    {
        return [
            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:school_activities,slug' . ($id ? ",{$id}" : ''),
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'gambar_utama' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
            ],
            'kategori' => [
                'nullable',
                'string',
                'max:100',
            ],
            'tanggal_mulai' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],
            'lokasi' => [
                'nullable',
                'string',
                'max:255',
            ],
            'penanggungjawab' => [
                'nullable',
                'string',
                'max:255',
            ],
            'aktif' => [
                'boolean',
            ],
            'unggulan' => [
                'boolean',
            ],
        ];
    }

    /**
     * Custom validation messages
     */
    public static function validationMessages(): array
    {
        return [
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'nama_kegiatan.max' => 'Nama kegiatan maksimal 255 karakter.',
            'slug.unique' => 'Slug sudah digunakan.',
            'deskripsi.max' => 'Deskripsi maksimal 5000 karakter.',
            'gambar_utama.image' => 'File harus berupa gambar.',
            'gambar_utama.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, GIF, WebP.',
            'gambar_utama.max' => 'Ukuran gambar maksimal 2MB.',
            'kategori.max' => 'Kategori maksimal 100 karakter.',
            'tanggal_mulai.after_or_equal' => 'Tanggal mulai tidak boleh kurang dari hari ini.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh kurang dari tanggal mulai.',
            'lokasi.max' => 'Lokasi maksimal 255 karakter.',
            'penanggungjawab.max' => 'Penanggung jawab maksimal 255 karakter.',
        ];
    }

    /**
     * Daftar kategori kegiatan yang tersedia
     */
    public static function getKategoriOptions(): array
    {
        return [
            'akademik' => 'Akademik',
            'ekstrakurikuler' => 'Ekstrakurikuler',
            'olahraga' => 'Olahraga',
            'seni_budaya' => 'Seni & Budaya',
            'keagamaan' => 'Keagamaan',
            'sosial' => 'Sosial',
            'kompetisi' => 'Kompetisi',
            'workshop' => 'Workshop/Pelatihan',
            'seminar' => 'Seminar',
            'lainnya' => 'Lainnya',
        ];
    }
}