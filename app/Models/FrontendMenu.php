<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CustomPage;

class FrontendMenu extends Model
{
    protected $fillable = [
        'title',
        'url',
        'route_name',
        'icon',
        'parent_id',
        'sort_order',
        'is_active',
        'open_in_new_tab',
        'css_class',
        'custom_page_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->ordered();
    }

    public function activeChildren(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->active()->ordered();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function customPage(): BelongsTo
    {
        return $this->belongsTo(CustomPage::class, 'custom_page_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }

    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Resolve the URL - prioritize: route_name > custom_page > raw url
     */
    public function getResolvedUrlAttribute(): string
    {
        if ($this->route_name) {
            try {
                return route($this->route_name);
            } catch (\Exception $e) {
                return $this->url ?? '#';
            }
        }

        if ($this->custom_page_id) {
            $page = $this->customPage;
            if ($page) {
                return route('custom-page.show', $page->slug);
            }
        }

        return $this->url ?? '#';
    }

    /**
     * Check if this menu item matches the current route
     */
    public function isCurrentRoute(): bool
    {
        // Custom page matching
        if ($this->custom_page_id) {
            $page = $this->customPage;
            if ($page && request()->routeIs('custom-page.show')) {
                return request()->route('slug') === $page->slug;
            }
            return false;
        }

        if (!$this->route_name) {
            return request()->url() === $this->url;
        }

        $routeName = $this->route_name;

        // Handle wildcard matching for route groups
        if (request()->routeIs($routeName)) {
            return true;
        }

        // Handle prefix matching (e.g., 'fasilitas.index' matches 'fasilitas.*')
        $prefix = explode('.', $routeName)[0];
        return request()->routeIs($prefix . '.*');
    }

    public function hasChildren(): bool
    {
        return $this->activeChildren()->count() > 0;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menu) {
            if (!$menu->sort_order) {
                $menu->sort_order = static::where('parent_id', $menu->parent_id)->max('sort_order') + 1;
            }
        });
    }
}
