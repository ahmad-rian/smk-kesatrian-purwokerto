<div x-data="{
    mobileMenuOpen: @entangle('mobileMenuOpen'),
    appearance: @entangle('appearance'),
    scrolled: false
}" x-init="// Handle scroll effect
window.addEventListener('scroll', () => {
    scrolled = window.scrollY > 10;
});

// Listen for theme changes
window.addEventListener('theme-changed', (event) => {
    appearance = event.detail.theme;
});">

    <nav class="bg-base-100/95 backdrop-blur-md border-b border-base-300 fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="{ 'shadow-lg bg-base-100': scrolled, 'bg-base-100/95': !scrolled }">

        <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="flex items-center justify-between h-18">

                {{-- Logo Section dengan Branding Text --}}
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="hover:opacity-80 transition-opacity" wire:navigate>
                        {{-- Logo Dinamis dari Database --}}
                        <x-app-logo size="lg" :showText="true" textPosition="right" logoClass="shadow-lg"
                            class="responsive-logo" />
                    </a>
                </div>

                {{-- Desktop Navigation --}}
                <div class="hidden lg:flex items-center space-x-8">
                    <nav class="flex items-center space-x-7" style="font-family: 'Inter', sans-serif;">
                        <a href="{{ route('home') }}"
                            class="text-base-content hover:text-primary transition-colors font-medium py-2"
                            wire:navigate>
                            Beranda
                        </a>
                        <a href="#profile"
                            class="text-base-content hover:text-primary transition-colors font-medium py-2">
                            Profile Sekolah
                        </a>
                        <a href="{{ route('kegiatan') }}"
                            class="text-base-content hover:text-primary transition-colors font-medium py-2"
                            wire:navigate>
                            Kegiatan
                        </a>
                        <a href="#jurusan"
                            class="text-base-content hover:text-primary transition-colors font-medium py-2">
                            Jurusan
                        </a>
                        <a href="#fasilitas"
                            class="text-base-content hover:text-primary transition-colors font-medium py-2">
                            Fasilitas
                        </a>
                        <a href="{{ route('berita') }}"
                            class="text-base-content hover:text-primary transition-colors font-medium py-2"
                            wire:navigate>
                            Berita
                        </a>
                        <a href="#contact"
                            class="text-base-content hover:text-primary transition-colors font-medium py-2">
                            Contact
                        </a>
                    </nav>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center space-x-3">

                    {{-- Theme Toggle Button --}}
                    <div class="relative" x-data="{ showTooltip: false }">
                        <button wire:click="toggleTheme" @mouseenter="showTooltip = true"
                            @mouseleave="showTooltip = false"
                            class="btn btn-ghost btn-circle btn-sm hover:btn-primary transition-all duration-300 group"
                            :class="{ 'btn-primary': appearance === 'dark' }">

                            {{-- Dynamic Icon dengan Alpine.js --}}
                            <div class="relative w-5 h-5">
                                {{-- Sun Icon (Light Mode) --}}
                                <x-mary-icon name="o-sun"
                                    class="w-5 h-5 absolute inset-0 transition-all duration-300 text-yellow-500"
                                    x-show="appearance === 'light'"
                                    x-transition:enter="transform transition ease-in-out duration-300"
                                    x-transition:enter-start="opacity-0 rotate-180 scale-50"
                                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                                    x-transition:leave="transform transition ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                                    x-transition:leave-end="opacity-0 -rotate-180 scale-50" />

                                {{-- Moon Icon (Dark Mode) --}}
                                <x-mary-icon name="o-moon"
                                    class="w-5 h-5 absolute inset-0 transition-all duration-300 text-slate-300"
                                    x-show="appearance === 'dark'"
                                    x-transition:enter="transform transition ease-in-out duration-300"
                                    x-transition:enter-start="opacity-0 rotate-180 scale-50"
                                    x-transition:enter-end="opacity-100 rotate-0 scale-100"
                                    x-transition:leave="transform transition ease-in-out duration-300"
                                    x-transition:leave-start="opacity-100 rotate-0 scale-100"
                                    x-transition:leave-end="opacity-0 -rotate-180 scale-50" />
                            </div>
                        </button>

                        {{-- Tooltip --}}
                        <div x-show="showTooltip" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg whitespace-nowrap z-50"
                            style="font-family: 'Inter', sans-serif;">
                            <span
                                x-text="appearance === 'light' ? 'Switch to Dark Mode' : 'Switch to Light Mode'"></span>
                            <div
                                class="absolute bottom-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-900">
                            </div>
                        </div>
                    </div>

                    {{-- Login Button (Desktop) --}}
                    <div class="hidden sm:block">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm"
                            style="font-family: 'Inter', sans-serif;" wire:navigate>
                            <x-mary-icon name="o-user" class="w-4 h-4" />
                            Masuk
                        </a>
                    </div>

                    {{-- Mobile Menu Toggle --}}
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden btn btn-ghost btn-circle btn-sm"
                        :class="{ 'btn-active': mobileMenuOpen }">
                        <div class="relative w-5 h-5">
                            {{-- Hamburger Icon --}}
                            <div class="absolute inset-0 transition-all duration-300"
                                :class="{ 'opacity-0 rotate-45': mobileMenuOpen, 'opacity-100 rotate-0': !mobileMenuOpen }">
                                <x-mary-icon name="o-bars-3" class="w-5 h-5" />
                            </div>
                            {{-- Close Icon --}}
                            <div class="absolute inset-0 transition-all duration-300"
                                :class="{ 'opacity-100 rotate-0': mobileMenuOpen, 'opacity-0 -rotate-45': !mobileMenuOpen }">
                                <x-mary-icon name="o-x-mark" class="w-5 h-5" />
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Mobile Navigation Menu --}}
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-4"
                class="lg:hidden border-t border-base-300 bg-base-100">

                <div class="px-4 py-6 space-y-4">
                    {{-- Mobile Navigation Links --}}
                    <nav class="space-y-3" style="font-family: 'Inter', sans-serif;">
                        <a href="{{ route('home') }}" @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base-content hover:text-primary hover:bg-primary/5 rounded-lg transition-all font-medium"
                            wire:navigate>
                            <div class="flex items-center space-x-3">
                                <x-mary-icon name="o-home" class="w-5 h-5" />
                                <span>Beranda</span>
                            </div>
                        </a>
                        <a href="#profile" @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base-content hover:text-primary hover:bg-primary/5 rounded-lg transition-all font-medium">
                            <div class="flex items-center space-x-3">
                                <x-mary-icon name="o-building-office-2" class="w-5 h-5" />
                                <span>Profile Sekolah</span>
                            </div>
                        </a>
                        <a href="#jurusan" @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base-content hover:text-primary hover:bg-primary/5 rounded-lg transition-all font-medium">
                            <div class="flex items-center space-x-3">
                                <x-mary-icon name="o-academic-cap" class="w-5 h-5" />
                                <span>Jurusan</span>
                            </div>
                        </a>
                        <a href="#fasilitas" @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base-content hover:text-primary hover:bg-primary/5 rounded-lg transition-all font-medium">
                            <div class="flex items-center space-x-3">
                                <x-mary-icon name="o-building-library" class="w-5 h-5" />
                                <span>Fasilitas</span>
                            </div>
                        </a>
                        <a href="{{ route('kegiatan') }}" @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base-content hover:text-primary hover:bg-primary/5 rounded-lg transition-all font-medium"
                            wire:navigate>
                            <div class="flex items-center space-x-3">
                                <x-mary-icon name="o-calendar-days" class="w-5 h-5" />
                                <span>Kegiatan</span>
                            </div>
                        </a>
                        <a href="{{ route('berita') }}" @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base-content hover:text-primary hover:bg-primary/5 rounded-lg transition-all font-medium"
                            wire:navigate>
                            <div class="flex items-center space-x-3">
                                <x-mary-icon name="o-newspaper" class="w-5 h-5" />
                                <span>Berita</span>
                            </div>
                        </a>
                        <a href="#contact" @click="mobileMenuOpen = false"
                            class="block px-4 py-3 text-base-content hover:text-primary hover:bg-primary/5 rounded-lg transition-all font-medium">
                            <div class="flex items-center space-x-3">
                                <x-mary-icon name="o-phone" class="w-5 h-5" />
                                <span>Contact</span>
                            </div>
                        </a>
                    </nav>

                    {{-- Mobile Login Button --}}
                    <div class="pt-4 border-t border-base-300">
                        <a href="{{ route('login') }}" @click="mobileMenuOpen = false"
                            class="flex items-center justify-center space-x-2 w-full btn btn-primary"
                            style="font-family: 'Inter', sans-serif;" wire:navigate>
                            <x-mary-icon name="o-user" class="w-4 h-4" />
                            <span>Masuk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Overlay untuk mobile menu --}}
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-25 z-40 lg:hidden"></div>

    {{-- Theme Management Script --}}
    <script>
        // Global theme management untuk frontend
        window.currentAppearance = @js(session('appearance', 'light'));

        // Theme application function
        window.applyTheme = function(appearance) {
            const html = document.documentElement;

            if (appearance === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = systemPrefersDark ? 'dark' : 'light';
                html.setAttribute('data-theme', theme);
            } else {
                html.setAttribute('data-theme', appearance);
            }

            window.currentAppearance = appearance;
        }

        // Initialize theme pada page load
        document.addEventListener('DOMContentLoaded', function() {
            // Apply initial theme
            window.applyTheme(window.currentAppearance);

            // Listen for system theme changes
            if (window.currentAppearance === 'system') {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                    if (window.currentAppearance === 'system') {
                        window.applyTheme('system');
                    }
                });
            }

            // Listen for theme changes from navbar
            window.addEventListener('theme-changed', function(event) {
                window.currentAppearance = event.detail.theme;
            });
        });

        // Listen for Livewire navigation
        document.addEventListener('livewire:navigated', function() {
            // Reapply theme after navigation
            if (window.applyTheme && window.currentAppearance) {
                window.applyTheme(window.currentAppearance);
            }
        });
    </script>

</div>
