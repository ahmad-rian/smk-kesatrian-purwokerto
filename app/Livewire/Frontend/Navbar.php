<?php

namespace App\Livewire\Frontend;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\SiteSetting;

/**
 * Navbar Component untuk Frontend
 * 
 * Component ini menangani navbar utama di frontend dengan fitur:
 * - Navigation menu responsif
 * - Dark/Light mode toggle
 * - Mobile menu toggle
 * - Theme switching dengan session storage
 */
class Navbar extends Component
{
    /**
     * Current theme appearance
     * Possible values: 'light', 'dark', 'system'
     */
    public string $appearance = 'light';

    /**
     * Mobile menu state
     */
    public bool $mobileMenuOpen = false;

    /**
     * Site settings data
     */
    public ?SiteSetting $siteSettings = null;

    /**
     * Initialize component dengan theme dari session
     */
    public function mount(): void
    {
        $this->appearance = session('appearance', 'light');
        $this->siteSettings = SiteSetting::first();
    }

    /**
     * Toggle theme antara light dan dark mode
     * Menggunakan cycle: light -> dark -> light
     */
    public function toggleTheme(): void
    {
        // Cycle through themes: light -> dark -> light
        $this->appearance = $this->appearance === 'light' ? 'dark' : 'light';

        // Save to session
        session(['appearance' => $this->appearance]);

        // Apply theme immediately dengan JavaScript
        $this->js("
            console.log('Theme toggled to: {$this->appearance}');
            
            // Apply theme using existing global function
            if (typeof window.applyTheme === 'function') {
                window.applyTheme('{$this->appearance}');
            } else {
                // Fallback direct DOM manipulation
                document.documentElement.setAttribute('data-theme', '{$this->appearance}');
            }
            
            // Dispatch event for other components
            window.dispatchEvent(new CustomEvent('theme-changed', { 
                detail: { theme: '{$this->appearance}' } 
            }));
        ");

        // Dispatch Livewire event untuk komponen lain
        $this->dispatch('theme-updated', appearance: $this->appearance);
    }

    /**
     * Set specific theme
     */
    public function setTheme(string $theme): void
    {
        if (!in_array($theme, ['light', 'dark', 'system'])) {
            return;
        }

        $this->appearance = $theme;
        session(['appearance' => $theme]);

        $this->js("
            if (typeof window.applyTheme === 'function') {
                window.applyTheme('{$theme}');
            } else {
                document.documentElement.setAttribute('data-theme', '{$theme}');
            }
        ");

        $this->dispatch('theme-updated', appearance: $theme);
    }

    /**
     * Toggle mobile menu
     */
    public function toggleMobileMenu(): void
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    /**
     * Close mobile menu
     */
    public function closeMobileMenu(): void
    {
        $this->mobileMenuOpen = false;
    }

    /**
     * Listen untuk theme updates dari komponen lain
     */
    #[On('appearance-updated')]
    public function onAppearanceUpdated($appearance): void
    {
        $this->appearance = $appearance;
    }

    /**
     * Check if current route is active
     * 
     * @param string $routeName
     * @return bool
     */
    public function isActiveRoute(string $routeName): bool
    {
        return request()->routeIs($routeName);
    }

    /**
     * Get active menu class
     * 
     * @param string $routeName
     * @return string
     */
    public function getActiveClass(string $routeName): string
    {
        return $this->isActiveRoute($routeName)
            ? 'text-primary bg-primary/10 border-primary/20'
            : 'text-base-content hover:text-primary hover:bg-primary/5';
    }

    /**
     * Get active mobile menu class
     * 
     * @param string $routeName
     * @return string
     */
    public function getActiveMobileClass(string $routeName): string
    {
        return $this->isActiveRoute($routeName)
            ? 'text-primary bg-primary/10 border-l-2 border-primary'
            : 'text-base-content hover:text-primary hover:bg-primary/5';
    }

    /**
     * Get current theme icon
     */
    public function getThemeIconProperty(): string
    {
        return match ($this->appearance) {
            'dark' => 'o-moon',
            'light' => 'o-sun',
            'system' => 'o-computer-desktop',
            default => 'o-sun'
        };
    }

    /**
     * Get theme toggle tooltip
     */
    public function getThemeTooltipProperty(): string
    {
        return match ($this->appearance) {
            'dark' => 'Switch to Light Mode',
            'light' => 'Switch to Dark Mode',
            'system' => 'Using System Theme',
            default => 'Toggle Theme'
        };
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.frontend.navbar', [
            'siteSettings' => $this->siteSettings
        ]);
    }
}
