<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Services\SitemapGenerator;
use App\Services\RobotsGenerator;
use App\View\Components\SeoMeta;
use App\View\Components\Breadcrumb;
use App\View\Components\OptimizedImage;

/**
 * SEO Service Provider
 * 
 * Provider untuk mendaftarkan semua service dan component SEO
 * termasuk sitemap generator, robots.txt, dan component view
 */
class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register SitemapGenerator sebagai singleton
        $this->app->singleton(SitemapGenerator::class, function ($app) {
            return new SitemapGenerator();
        });
        
        // Register RobotsGenerator sebagai singleton
        $this->app->singleton(RobotsGenerator::class, function ($app) {
            return new RobotsGenerator();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register Blade components
        $this->registerBladeComponents();
        
        // Register view composers untuk SEO data
        $this->registerViewComposers();
        
        // Register custom directives
        $this->registerBladeDirectives();
    }
    
    /**
     * Register semua Blade components untuk SEO
     */
    private function registerBladeComponents(): void
    {
        // Register SEO Meta component
        Blade::component('seo-meta', SeoMeta::class);
        
        // Register Breadcrumb component
        Blade::component('breadcrumb', Breadcrumb::class);
        
        // Register Optimized Image component
        Blade::component('optimized-image', OptimizedImage::class);
    }
    
    /**
     * Register view composers untuk data SEO global
     */
    private function registerViewComposers(): void
    {
        // Composer untuk data SEO global
        view()->composer('*', function ($view) {
            $siteSettings = cache()->remember('site_settings', 3600, function () {
                return \App\Models\SiteSetting::first() ?? new \App\Models\SiteSetting();
            });
            
            $view->with('siteSettings', $siteSettings);
        });
        
        // Composer khusus untuk layout dengan data SEO
        view()->composer(['layouts.app', 'layouts.guest'], function ($view) {
            $defaultSeo = [
                'title' => 'SMK Kesatrian - Sekolah Menengah Kejuruan Terbaik',
                'description' => 'SMK Kesatrian adalah sekolah menengah kejuruan yang menghasilkan lulusan berkualitas dan siap kerja di berbagai bidang industri.',
                'keywords' => 'SMK, sekolah kejuruan, pendidikan, teknologi, industri, lulusan berkualitas',
                'image' => asset('images/logo-smk.png'),
                'url' => request()->url()
            ];
            
            $view->with('defaultSeo', $defaultSeo);
        });
    }
    
    /**
     * Register custom Blade directives untuk SEO
     */
    private function registerBladeDirectives(): void
    {
        // Directive untuk generate canonical URL
        Blade::directive('canonical', function ($expression) {
            return "<?php echo '<link rel=\"canonical\" href=\"' . ($expression ?: request()->url()) . '\" />'; ?>";
        });
        
        // Directive untuk generate meta robots
        Blade::directive('robots', function ($expression) {
            $default = 'index,follow';
            return "<?php echo '<meta name=\"robots\" content=\"' . ($expression ?: '$default') . '\" />'; ?>";
        });
        
        // Directive untuk generate hreflang
        Blade::directive('hreflang', function ($expression) {
            return "<?php 
                if ($expression) {
                    foreach ($expression as \$lang => \$url) {
                        echo '<link rel=\"alternate\" hreflang=\"' . \$lang . '\" href=\"' . \$url . '\" />' . PHP_EOL;
                    }
                }
            ?>";
        });
        
        // Directive untuk structured data JSON-LD
        Blade::directive('jsonld', function ($expression) {
            return "<?php 
                if ($expression) {
                    echo '<script type=\"application/ld+json\">' . json_encode($expression, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
                }
            ?>";
        });
    }
}