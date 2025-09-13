<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk mengelola pesan kontak dari pengunjung website
 * 
 * @property string $id
 * @property string $nama
 * @property string $email
 * @property string|null $telepon
 * @property string $subjek
 * @property string $pesan
 * @property string $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class ContactMessage extends Model
{
    use HasUlids;

    /**
     * Status pesan kontak
     */
    const STATUS_UNREAD = 'belum_dibaca';
    const STATUS_READ = 'sudah_dibaca';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'subjek',
        'pesan',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Scope untuk mendapatkan pesan yang belum dibaca
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->where('status', self::STATUS_UNREAD);
    }

    /**
     * Scope untuk mendapatkan pesan yang sudah dibaca
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRead($query)
    {
        return $query->where('status', self::STATUS_READ);
    }

    /**
     * Menandai pesan sebagai sudah dibaca
     *
     * @return bool
     */
    public function markAsRead(): bool
    {
        return $this->update(['status' => self::STATUS_READ]);
    }

    /**
     * Menandai pesan sebagai belum dibaca
     *
     * @return bool
     */
    public function markAsUnread(): bool
    {
        return $this->update(['status' => self::STATUS_UNREAD]);
    }

    /**
     * Cek apakah pesan sudah dibaca
     *
     * @return bool
     */
    public function isRead(): bool
    {
        return $this->status === self::STATUS_READ;
    }

    /**
     * Cek apakah pesan belum dibaca
     *
     * @return bool
     */
    public function isUnread(): bool
    {
        return $this->status === self::STATUS_UNREAD;
    }
}