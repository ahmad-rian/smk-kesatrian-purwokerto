<div x-data="{
    mobileMenuOpen: @entangle('mobileMenuOpen'),
    appearance: @entangle('appearance'),
    scrolled: false,
    isFloating: false
}" @scroll.window="
    scrolled = window.scrollY > 10;
    isFloating = window.scrollY > 100;
"
    x-init="scrolled = window.scrollY > 10;
    isFloating = window.scrollY > 100;">

    <!-- Navbar Container with simple top animation -->
    <div class="z-50"
        :class="{
            'relative w-full': !isFloating,
            'fixed top-4 left-1/2 transform -translate-x-1/2 w-[95%] lg:w-[85%] xl:w-[70%] max-w-6xl': isFloating
        }">

        <!-- Main Navbar -->
        <nav class="transition-all duration-500 ease-out"
            :class="{
                'bg-base-100/95 backdrop-blur-sm border-b border-base-300/60 shadow-sm': !isFloating,
                'bg-base-100/95 backdrop-blur-xl border border-base-300/60 rounded-2xl shadow-2xl': isFloating
            }">

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 transition-all duration-500"
                :class="{
                    'py-2 sm:py-3': !isFloating,
                    'py-2': isFloating
                }">

                <div class="flex items-center justify-between transition-all duration-500"
                    :class="{
                        'h-12 sm:h-14': !isFloating,
                        'h-10 sm:h-12': isFloating
                    }">

                    <!-- Logo Section -->
                    <div class="flex items-center space-x-2 sm:space-x-3 transition-all duration-500"
                        :class="{
                            'scale-100': !isFloating,
                            'scale-95': isFloating
                        }">
                        <a href="{{ route('home') }}"
                            class="hover:opacity-80 transition-opacity flex items-center space-x-2 sm:space-x-3"
                            wire:navigate>
                            <!-- Logo Image atau Icon -->
                            @if ($siteSettings && $siteSettings->logo)
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full overflow-hidden shadow-md">
                                    <img src="{{ Storage::url($siteSettings->logo) }}"
                                        alt="Logo {{ $siteSettings->nama_sekolah }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div
                                    class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center shadow-md">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Logo Text -->
                            <div class="flex flex-col" style="font-family: 'Bricolage Grotesque', sans-serif;">
                                <span class="text-sm sm:text-base font-bold text-base-content leading-tight">
                                    {{ $siteSettings->nama_singkat ?? 'SMK KESATRIAN' }}
                                </span>
                                <span class="text-xs text-base-content/70 leading-tight">
                                    {{ $siteSettings->tagline ?? 'Sekolah Menengah Kejuruan' }}
                                </span>
                            </div>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-4 xl:space-x-6">
                        <nav class="flex items-center space-x-1" style="font-family: 'Inter', sans-serif;">
                            <a href="{{ route('home') }}"
                                class="relative {{ $this->getActiveClass('home') }} transition-all duration-300 font-medium py-2 px-3 rounded-lg text-sm"
                                wire:navigate>
                                Beranda
                                @if ($this->isActiveRoute('home'))
                                    <div
                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full">
                                    </div>
                                @endif
                            </a>
                            <a href="{{ route('profil') }}"
                                class="relative {{ $this->getActiveClass('profil') }} transition-all duration-300 font-medium py-2 px-3 rounded-lg text-sm"
                                wire:navigate>
                                Profil Sekolah
                                @if ($this->isActiveRoute('profil'))
                                    <div
                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full">
                                    </div>
                                @endif
                            </a>
                            <a href="{{ route('kegiatan') }}"
                                class="relative {{ $this->getActiveClass('kegiatan') }} transition-all duration-300 font-medium py-2 px-3 rounded-lg text-sm"
                                wire:navigate>
                                Kegiatan
                                @if ($this->isActiveRoute('kegiatan'))
                                    <div
                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full">
                                    </div>
                                @endif
                            </a>
                            <a href="{{ route('jurusan') }}"
                                class="relative {{ $this->getActiveClass('jurusan') }} transition-all duration-300 font-medium py-2 px-3 rounded-lg text-sm"
                                wire:navigate>
                                Jurusan
                                @if ($this->isActiveRoute('jurusan'))
                                    <div
                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full">
                                    </div>
                                @endif
                            </a>
                            <a href="{{ route('fasilitas.index') }}"
                                class="relative {{ $this->getActiveClass('fasilitas') }} transition-all duration-300 font-medium py-2 px-3 rounded-lg text-sm"
                                wire:navigate>
                                Fasilitas
                                @if ($this->isActiveRoute('fasilitas'))
                                    <div
                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full">
                                    </div>
                                @endif
                            </a>
                            <a href="{{ route('berita') }}"
                                class="relative {{ $this->getActiveClass('berita') }} transition-all duration-300 font-medium py-2 px-3 rounded-lg text-sm"
                                wire:navigate>
                                Berita
                                @if ($this->isActiveRoute('berita'))
                                    <div
                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full">
                                    </div>
                                @endif
                            </a>
                            <a href="{{ route('kontak') }}"
                                class="relative {{ $this->getActiveClass('kontak') }} transition-all duration-300 font-medium py-2 px-3 rounded-lg text-sm"
                                wire:navigate>
                                Kontak
                                @if ($this->isActiveRoute('kontak'))
                                    <div
                                        class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1 h-1 bg-primary rounded-full">
                                    </div>
                                @endif
                            </a>
                        </nav>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2 sm:space-x-3 transition-all duration-500"
                        :class="{
                            'scale-100': !isFloating,
                            'scale-95': isFloating
                        }">

                        <!-- Theme Toggle Button -->
                        <div class="relative" x-data="{ showTooltip: false }">
                            <button wire:click="toggleTheme" @mouseenter="showTooltip = true"
                                @mouseleave="showTooltip = false"
                                class="btn btn-ghost btn-circle btn-sm hover:btn-primary transition-all duration-300 w-8 h-8 sm:w-9 sm:h-9"
                                :class="{ 'btn-primary': appearance === 'dark' }">

                                <!-- Dynamic Icon -->
                                <div class="relative w-4 h-4 sm:w-5 sm:h-5">
                                    <!-- Sun Icon -->
                                    <x-mary-icon name="o-sun"
                                        class="w-4 h-4 sm:w-5 sm:h-5 absolute inset-0 transition-all duration-300 text-yellow-500"
                                        x-show="appearance === 'light'"
                                        x-transition:enter="transform transition ease-in-out duration-300"
                                        x-transition:enter-start="opacity-0 rotate-180 scale-50"
                                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                                        x-transition:leave="transform transition ease-in-out duration-300"
                                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                                        x-transition:leave-end="opacity-0 -rotate-180 scale-50" />

                                    <!-- Moon Icon -->
                                    <x-mary-icon name="o-moon"
                                        class="w-4 h-4 sm:w-5 sm:h-5 absolute inset-0 transition-all duration-300 text-slate-300"
                                        x-show="appearance === 'dark'"
                                        x-transition:enter="transform transition ease-in-out duration-300"
                                        x-transition:enter-start="opacity-0 rotate-180 scale-50"
                                        x-transition:enter-end="opacity-100 rotate-0 scale-100"
                                        x-transition:leave="transform transition ease-in-out duration-300"
                                        x-transition:leave-start="opacity-100 rotate-0 scale-100"
                                        x-transition:leave-end="opacity-0 -rotate-180 scale-50" />
                                </div>
                            </button>

                            <!-- Tooltip -->
                            <div x-show="showTooltip" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform scale-100"
                                x-transition:leave-end="opacity-0 transform scale-95"
                                class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 px-3 py-1 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-xl whitespace-nowrap z-50"
                                style="font-family: 'Inter', sans-serif;">
                                <span
                                    x-text="appearance === 'light' ? 'Switch to Dark Mode' : 'Switch to Light Mode'"></span>
                                <div
                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-b-4 border-transparent border-b-gray-900">
                                </div>
                            </div>
                        </div>

                        <!-- Login Button (Desktop) -->
                        <div class="hidden sm:block">
                            <a href="{{ route('login') }}"
                                class="btn btn-primary btn-sm hover:scale-105 transition-transform duration-300 text-xs px-3 py-1"
                                style="font-family: 'Inter', sans-serif;" wire:navigate>
                                <x-mary-icon name="o-user" class="w-3 h-3 sm:w-4 sm:h-4" />
                                <span class="hidden sm:inline">Masuk</span>
                            </a>
                        </div>

                        <!-- Mobile Menu Toggle -->
                        <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="lg:hidden btn btn-ghost btn-circle btn-sm w-8 h-8 sm:w-9 sm:h-9"
                            :class="{ 'btn-active': mobileMenuOpen }">
                            <div class="relative w-4 h-4 sm:w-5 sm:h-5">
                                <!-- Hamburger Icon -->
                                <div class="absolute inset-0 transition-all duration-300"
                                    :class="{ 'opacity-0 rotate-45': mobileMenuOpen, 'opacity-100 rotate-0': !mobileMenuOpen }">
                                    <x-mary-icon name="o-bars-3" class="w-4 h-4 sm:w-5 sm:h-5" />
                                </div>
                                <!-- Close Icon -->
                                <div class="absolute inset-0 transition-all duration-300"
                                    :class="{ 'opacity-100 rotate-0': mobileMenuOpen, 'opacity-0 -rotate-45': !mobileMenuOpen }">
                                    <x-mary-icon name="o-x-mark" class="w-4 h-4 sm:w-5 sm:h-5" />
                                </div>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4"
                    class="lg:hidden border-t border-base-300 bg-base-100"
                    :class="{
                        'mt-2 rounded-b-2xl': isFloating,
                        'mt-0': !isFloating
                    }">

                    <div class="px-4 py-4 space-y-2">
                        <!-- Mobile Navigation Links -->
                        <nav class="space-y-1" style="font-family: 'Inter', sans-serif;">
                            <a href="{{ route('home') }}" @click="mobileMenuOpen = false"
                                class="block px-3 py-2 {{ $this->getActiveMobileClass('home') }} rounded-lg transition-all font-medium text-sm"
                                wire:navigate>
                                <div class="flex items-center space-x-3">
                                    <x-mary-icon name="o-home" class="w-4 h-4" />
                                    <span>Beranda</span>
                                </div>
                            </a>
                            <a href="{{ route('profil') }}" @click="mobileMenuOpen = false"
                                class="block px-3 py-2 {{ $this->getActiveMobileClass('profil') }} rounded-lg transition-all font-medium text-sm"
                                wire:navigate>
                                <div class="flex items-center space-x-3">
                                    <x-mary-icon name="o-building-office-2" class="w-4 h-4" />
                                    <span>Profil Sekolah</span>
                                </div>
                            </a>
                            <a href="{{ route('jurusan') }}" @click="mobileMenuOpen = false"
                                class="block px-3 py-2 {{ $this->getActiveMobileClass('jurusan') }} rounded-lg transition-all font-medium text-sm"
                                wire:navigate>
                                <div class="flex items-center space-x-3">
                                    <x-mary-icon name="o-academic-cap" class="w-4 h-4" />
                                    <span>Jurusan</span>
                                </div>
                            </a>
                            <a href="{{ route('fasilitas.index') }}" @click="mobileMenuOpen = false"
                                class="block px-3 py-2 {{ $this->getActiveMobileClass('fasilitas') }} rounded-lg transition-all font-medium text-sm"
                                wire:navigate>
                                <div class="flex items-center space-x-3">
                                    <x-mary-icon name="o-building-library" class="w-4 h-4" />
                                    <span>Fasilitas</span>
                                </div>
                            </a>
                            <a href="{{ route('kegiatan') }}" @click="mobileMenuOpen = false"
                                class="block px-3 py-2 {{ $this->getActiveMobileClass('kegiatan') }} rounded-lg transition-all font-medium text-sm"
                                wire:navigate>
                                <div class="flex items-center space-x-3">
                                    <x-mary-icon name="o-calendar-days" class="w-4 h-4" />
                                    <span>Kegiatan</span>
                                </div>
                            </a>
                            <a href="{{ route('berita') }}" @click="mobileMenuOpen = false"
                                class="block px-3 py-2 {{ $this->getActiveMobileClass('berita') }} rounded-lg transition-all font-medium text-sm"
                                wire:navigate>
                                <div class="flex items-center space-x-3">
                                    <x-mary-icon name="o-newspaper" class="w-4 h-4" />
                                    <span>Berita</span>
                                </div>
                            </a>
                            <a href="{{ route('kontak') }}" @click="mobileMenuOpen = false"
                                class="block px-3 py-2 {{ $this->getActiveMobileClass('kontak') }} rounded-lg transition-all font-medium text-sm"
                                wire:navigate>
                                <div class="flex items-center space-x-3">
                                    <x-mary-icon name="o-phone" class="w-4 h-4" />
                                    <span>Kontak</span>
                                </div>
                            </a>
                        </nav>

                        <!-- Mobile Login Button -->
                        <div class="pt-3 border-t border-base-300">
                            <a href="{{ route('login') }}" @click="mobileMenuOpen = false"
                                class="flex items-center justify-center space-x-2 w-full btn btn-primary btn-sm"
                                style="font-family: 'Inter', sans-serif;" wire:navigate>
                                <x-mary-icon name="o-user" class="w-4 h-4" />
                                <span>Masuk</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Overlay untuk mobile menu -->
    <div x-show="mobileMenuOpen" @click="mobileMenuOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-25 z-40 lg:hidden"></div>

</div>

<script>
    // Theme management tanpa const untuk kompatibilitas
    window.currentAppearance = @json(session('appearance', 'light'));

    function applyTheme(appearance) {
        var html = document.documentElement;
        if (appearance === 'system') {
            var systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = systemPrefersDark ? 'dark' : 'light';
            html.setAttribute('data-theme', theme);
        } else {
            html.setAttribute('data-theme', appearance);
        }
        window.currentAppearance = appearance;
    }

    document.addEventListener('DOMContentLoaded', function() {
        applyTheme(window.currentAppearance);

        if (window.currentAppearance === 'system') {
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                if (window.currentAppearance === 'system') {
                    applyTheme('system');
                }
            });
        }

        window.addEventListener('theme-changed', function(event) {
            window.currentAppearance = event.detail.theme;
        });
    });

    document.addEventListener('livewire:navigated', function() {
        if (window.applyTheme && window.currentAppearance) {
            applyTheme(window.currentAppearance);
        }
    });
</script>
