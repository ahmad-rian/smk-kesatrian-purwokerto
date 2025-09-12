<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageConversionService;

/**
 * Model untuk mengelola pengaturan situs
 * 
 * @property string $id
 * @property string $nama_sekolah
 * @property string|null $nama_singkat
 * @property string|null $tagline
 * @property string|null $deskripsi
 * @property string|null $logo
 * @property string $alamat
 * @property string|null $telepon
 * @property string|null $email
 * @property string|null $website
 * @property array|null $media_sosial
 * @property string|null $visi
 * @property string|null $misi
 * @property string|null $nama_kepala_sekolah
 * @property string|null $foto_kepala_sekolah
 * @property int|null $tahun_berdiri
 */
class SiteSetting extends Model
{
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_sekolah',
        'nama_singkat',
        'tahun_berdiri',
        'tagline',
        'deskripsi',
        'logo',
        'alamat',
        'telepon',
        'email',
        'website',
        'media_sosial',
        'visi',
        'misi',
        'nama_kepala_sekolah',
        'foto_kepala_sekolah',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tahun_berdiri' => 'integer',
        'media_sosial' => 'array',
    ];

    /**
     * Mutator untuk logo - konversi otomatis ke WebP
     */
    public function setLogoAttribute($value)
    {
        if ($value && is_file($value)) {
            $imageService = new ImageConversionService();
            $this->attributes['logo'] = $imageService->convertToWebP($value, 'logos');
        } else {
            $this->attributes['logo'] = $value;
        }
    }

    /**
     * Mutator untuk foto kepala sekolah - konversi otomatis ke WebP
     */
    public function setFotoKepalaSekolahAttribute($value)
    {
        if ($value && is_file($value)) {
            $imageService = new ImageConversionService();
            $this->attributes['foto_kepala_sekolah'] = $imageService->convertToWebP($value, 'kepala-sekolah');
        } else {
            $this->attributes['foto_kepala_sekolah'] = $value;
        }
    }

    /**
     * Accessor untuk URL logo
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? Storage::url($this->logo) : null;
    }

    /**
     * Accessor untuk URL foto kepala sekolah
     */
    public function getFotoKepalaSekolahUrlAttribute()
    {
        return $this->foto_kepala_sekolah ? Storage::url($this->foto_kepala_sekolah) : null;
    }

    /**
     * Accessor untuk mendapatkan Instagram dari media sosial
     *
     * @return string|null
     */
    public function getInstagramAttribute(): ?string
    {
        return $this->media_sosial['instagram'] ?? null;
    }

    /**
     * Accessor untuk mendapatkan Facebook dari media sosial
     *
     * @return string|null
     */
    public function getFacebookAttribute(): ?string
    {
        return $this->media_sosial['facebook'] ?? null;
    }

    /**
     * Accessor untuk mendapatkan YouTube dari media sosial
     *
     * @return string|null
     */
    public function getYoutubeAttribute(): ?string
    {
        return $this->media_sosial['youtube'] ?? null;
    }

    /**
     * Accessor untuk mendapatkan TikTok dari media sosial
     *
     * @return string|null
     */
    public function getTiktokAttribute(): ?string
    {
        return $this->media_sosial['tiktok'] ?? null;
    }

    /**
     * Scope untuk mendapatkan pengaturan aktif (biasanya hanya ada satu record)
     */
    public function scopeActive($query)
    {
        return $query->latest()->first();
    }

    /**
     * Method untuk mendapatkan instance pengaturan situs
     * Jika belum ada, buat record baru dengan data default
     */
    public static function getInstance()
    {
        $setting = static::first();
        
        if (!$setting) {
            $setting = static::create([
                'nama_sekolah' => 'SMK Kesatrian',
                'alamat' => 'Alamat Sekolah',
            ]);
        }
        
        return $setting;
    }

    /**
     * Method untuk membersihkan file gambar lama saat update
     */
    public function cleanOldImages(array $newData)
    {
        $imageFields = ['logo', 'foto_kepala_sekolah'];
        
        foreach ($imageFields as $field) {
            if (isset($newData[$field]) && $this->$field && $this->$field !== $newData[$field]) {
                Storage::delete($this->$field);
            }
        }
    }

    /**
     * Override delete method untuk membersihkan file gambar
     */
    public function delete()
    {
        // Hapus file gambar terkait
        if ($this->logo) {
            Storage::delete($this->logo);
        }
        
        if ($this->foto_kepala_sekolah) {
            Storage::delete($this->foto_kepala_sekolah);
        }
        
        return parent::delete();
    }
}