<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Daftarkan SEO Meta Component
        Blade::component('seo-meta', \App\View\Components\SeoMeta::class);
        
        // Daftarkan Lazy Image Component
        Blade::component('lazy-image', \App\View\Components\LazyImage::class);
        
        // Daftarkan Structured Data Component
        Blade::component('structured-data', \App\View\Components\StructuredData::class);
    }
}
