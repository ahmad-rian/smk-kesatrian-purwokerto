<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Services\ImageConversionService;
use Illuminate\Support\Facades\Storage;

/**
 * Model untuk Program Studi
 * 
 * Mengelola data program studi dengan fitur:
 * - ULID sebagai primary key
 * - Konversi gambar otomatis ke WebP
 * - JSON casting untuk kompetensi dan prospek karir
 * - Scope untuk program aktif dan urutan
 */
class StudyProgram extends Model
{
    use HasFactory, HasUlids;

    /**
     * Nama tabel database
     */
    protected $table = 'study_programs';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'gambar',
        'warna',
        'kompetensi',
        'prospek_karir',
        'ketua_program',
        'aktif',
        'urutan',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'kompetensi' => 'array',
        'prospek_karir' => 'array',
        'aktif' => 'boolean',
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
                'study-programs/images'
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
     * Scope untuk program studi aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk mengurutkan berdasarkan urutan
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan')->orderBy('nama');
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('kode', 'like', "%{$search}%")
              ->orWhere('nama', 'like', "%{$search}%")
              ->orWhere('ketua_program', 'like', "%{$search}%");
        });
    }

    /**
     * Boot method untuk event handling
     */
    protected static function boot()
    {
        parent::boot();

        // Hapus gambar saat model dihapus
        static::deleting(function ($studyProgram) {
            if ($studyProgram->gambar) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($studyProgram->gambar);
            }
        });
    }

    /**
     * Validasi rules untuk model
     */
    public static function validationRules($id = null): array
    {
        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z0-9]+$/',
                'unique:study_programs,kode' . ($id ? ",{$id}" : ''),
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048', // 2MB
            ],
            'warna' => [
                'required',
                'string',
                'regex:/^#[0-9A-Fa-f]{6}$/',
            ],
            'kompetensi' => [
                'nullable',
                'array',
            ],
            'kompetensi.*' => [
                'string',
                'max:500',
            ],
            'prospek_karir' => [
                'nullable',
                'array',
            ],
            'prospek_karir.*' => [
                'string',
                'max:500',
            ],
            'ketua_program' => [
                'nullable',
                'string',
                'max:255',
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
     * Custom validation messages
     */
    public static function validationMessages(): array
    {
        return [
            'kode.required' => 'Kode program studi wajib diisi.',
            'kode.unique' => 'Kode program studi sudah digunakan.',
            'kode.regex' => 'Kode program studi hanya boleh menggunakan huruf kapital dan angka.',
            'nama.required' => 'Nama program studi wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, GIF, WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'warna.required' => 'Warna tema wajib dipilih.',
            'warna.regex' => 'Format warna harus berupa kode hex (contoh: #3b82f6).',
            'kompetensi.*.max' => 'Setiap kompetensi maksimal 500 karakter.',
            'prospek_karir.*.max' => 'Setiap prospek karir maksimal 500 karakter.',
        ];
    }
}