<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Model untuk mengelola kategori program studi
 *
 * Fitur:
 * - Manajemen kategori dengan warna dan ikon
 * - Slug otomatis dari nama
 * - Relasi ke program studi
 * - Scope untuk kategori aktif
 */
class StudyProgramCategory extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'study_program_categories';

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
     * Relasi ke program studi
     */
    public function studyPrograms(): HasMany
    {
        return $this->hasMany(StudyProgram::class, 'study_program_category_id');
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
     * Get jumlah program studi dalam kategori
     */
    public function getStudyProgramsCountAttribute(): int
    {
        return $this->studyPrograms()->count();
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
        return 'id';
    }

    /**
     * Validasi rules untuk model
     */
    public static function validationRules($id = null): array
    {
        return [
            'name' => 'required|string|max:255|unique:study_program_categories,name' . ($id ? ",{$id}" : ''),
            'slug' => 'required|string|max:255|unique:study_program_categories,slug' . ($id ? ",{$id}" : ''),
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ];
    }

    /**
     * Custom validation messages
     */
    public static function validationMessages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan.',
            'color.required' => 'Warna wajib dipilih.',
            'color.regex' => 'Format warna tidak valid.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
            'sort_order.min' => 'Urutan tidak boleh negatif.'
        ];
    }
}
