<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class CustomPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'meta_description',
        'featured_image',
        'show_hero',
        'hero_style',
        'is_published',
    ];

    protected $casts = [
        'show_hero' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function menu(): HasOne
    {
        return $this->hasOne(FrontendMenu::class, 'custom_page_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
                $original = $page->slug;
                $count = 1;
                while (static::where('slug', $page->slug)->exists()) {
                    $page->slug = $original . '-' . $count++;
                }
            }
        });
    }
}
