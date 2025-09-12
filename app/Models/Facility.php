<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use App\Services\ImageConversionService;

/**
 * Model untuk Fasilitas Sekolah
 * 
 * Mengelola data fasilitas sekolah dengan fitur:
 * - ULID sebagai primary key
 * - Konversi gambar otomatis ke WebP
 * - Relasi dengan StudyProgram
 * - Scope untuk fasilitas aktif dan urutan
 * 
 * @property string $id
 * @property string $nama
 * @property string|null $kategori
 * @property string|null $deskripsi
 * @property string|null $gambar
 * @property string|null $study_program_id
 * @property bool $aktif
 * @property int $urutan
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class Facility extends Model
{
    use HasFactory, HasUlids;

    /**
     * Nama tabel database
     */
    protected $table = 'facilities';

    /**
     * Field yang dapat diisi mass assignment
     */
    protected $fillable = [
        'nama',
        'kategori',
        'deskripsi',
        'gambar',
        'study_program_id',
        'aktif',
        'urutan',
    ];

    /**
     * Casting tipe data
     */
    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Boot method untuk model events
     */
    protected static function boot(): void
    {
        parent::boot();

        // Auto-generate urutan saat create
        static::creating(function ($facility) {
            if (empty($facility->urutan)) {
                $facility->urutan = static::max('urutan') + 1;
            }
        });

        // Hapus gambar saat model dihapus
        static::deleting(function ($facility) {
            if ($facility->gambar && Storage::disk('public')->exists($facility->gambar)) {
                Storage::disk('public')->delete($facility->gambar);
            }
        });
    }

    /**
     * Relasi dengan StudyProgram
     * Fasilitas bisa terkait dengan program studi tertentu
     */
    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    /**
     * Scope untuk fasilitas aktif
     */
    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Scope untuk urutan fasilitas
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByCategory($query, $kategori)
    {
        if ($kategori && $kategori !== 'all') {
            return $query->where('kategori', $kategori);
        }
        return $query;
    }

    /**
     * Scope untuk pencarian
     */
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhereHas('studyProgram', function ($sq) use ($search) {
                      $sq->where('nama', 'like', "%{$search}%");
                  });
            });
        }
        return $query;
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
     * Method untuk hapus gambar
     */
    public function deleteImage(): bool
    {
        if (!$this->gambar) {
            return true;
        }
        
        $imageService = app(ImageConversionService::class);
        $deleted = $imageService->deleteOldImage($this->gambar);
        
        if ($deleted) {
            $this->update(['gambar' => null]);
        }
        
        return $deleted;
    }

    /**
     * Method untuk mendapatkan daftar kategori yang tersedia
     */
    public static function getAvailableCategories(): array
    {
        return [
            'laboratorium' => 'Laboratorium',
            'ruang_kelas' => 'Ruang Kelas',
            'perpustakaan' => 'Perpustakaan',
            'olahraga' => 'Fasilitas Olahraga',
            'workshop' => 'Workshop/Bengkel',
            'kantin' => 'Kantin',
            'asrama' => 'Asrama',
            'musholla' => 'Musholla/Tempat Ibadah',
            'parkir' => 'Area Parkir',
            'lainnya' => 'Lainnya',
        ];
    }

    /**
     * Accessor untuk nama kategori yang readable
     */
    public function getCategoryNameAttribute(): string
    {
        $categories = static::getAvailableCategories();
        return $categories[$this->kategori] ?? $this->kategori ?? 'Tidak Dikategorikan';
    }

    /**
     * Method untuk mendapatkan statistik fasilitas
     */
    public static function getStats(): array
    {
        return [
            'total' => static::count(),
            'active' => static::where('aktif', true)->count(),
            'inactive' => static::where('aktif', false)->count(),
            'by_category' => static::selectRaw('kategori, COUNT(*) as count')
                ->whereNotNull('kategori')
                ->groupBy('kategori')
                ->pluck('count', 'kategori')
                ->toArray(),
        ];
    }
}