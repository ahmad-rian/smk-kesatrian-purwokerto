<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Model untuk menyimpan summary visitor berita per hari
 * Optimasi database dengan menyimpan agregat data visitor
 */
class NewsVisitorSummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'news_id',
        'visit_date',
        'unique_visitors',
        'total_visits',
        'visitor_ips'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visitor_ips' => 'array',
        'unique_visitors' => 'integer',
        'total_visits' => 'integer'
    ];

    /**
     * Relasi ke News
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Scope untuk hari ini
     */
    public function scopeToday($query)
    {
        return $query->where('visit_date', today());
    }

    /**
     * Scope untuk range tanggal
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('visit_date', [$startDate, $endDate]);
    }
}
