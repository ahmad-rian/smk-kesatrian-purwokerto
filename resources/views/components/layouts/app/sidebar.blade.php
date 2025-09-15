<!-- Enhanced MaryUI Sidebar for SPA -->
<div class="drawer lg:drawer-open">
    <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

    <!-- Sidebar Content -->
    <div class="drawer-side z-40">
        <label for="drawer-toggle" class="drawer-overlay lg:hidden"></label>
        <aside class="min-h-full w-64 bg-base-200 text-base-content shadow-lg">

            <!-- Logo Section -->
            <div class="p-4 border-b border-base-300">
                <a href="{{ route('admin.dashboard') }}" class="transition-all hover:scale-105" wire:navigate>
                    {{-- Logo Dinamis dari Database untuk Admin Sidebar --}}
                    <x-app-logo size="md" :showText="true" textPosition="right" logoClass="shadow-sm"
                        class="gap-3" />
                </a>
            </div>

            <!-- Navigation Menu -->
            <div class="flex-1 overflow-y-auto">
                <x-mary-menu class="p-2">
                    <!-- Platform Section -->
                    <x-mary-menu-title :title="__('Platform')" />

                    <x-mary-menu-item :title="__('Dashboard')" icon="o-home" :link="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate
                        class="spa-nav-item" />

                    <!-- Settings Section -->
                    <div class="mt-4">
                        <x-mary-menu-title title="Pengaturan" />

                        <!-- Site Settings -->
                        <x-mary-menu-item title="Pengaturan Situs" icon="o-cog-6-tooth" :link="route('admin.site-settings')"
                            :active="request()->routeIs('admin.site-settings')" wire:navigate class="spa-nav-item" />
                    </div>

                    <!-- Academic Section -->
                    <div class="mt-4">
                        <x-mary-menu-title title="Akademik" />

                        <!-- Study Programs -->
                        <x-mary-menu-item title="Program Studi" icon="o-academic-cap" :link="route('admin.study-programs.index')"
                            :active="request()->routeIs('admin.study-programs.*')" wire:navigate class="spa-nav-item" />

                        <!-- Kegiatan Sekolah -->
                        <x-mary-menu-item title="Kegiatan Sekolah" icon="o-calendar-days" :link="route('admin.school-activities.index')"
                            :active="request()->routeIs('admin.school-activities.*')" wire:navigate class="spa-nav-item" />

                        <!-- Berita -->
                        <x-mary-menu-item title="Berita" icon="o-newspaper" :link="route('admin.news.index')" :active="request()->routeIs('admin.news.*')"
                            wire:navigate class="spa-nav-item" />

                        <!-- Fasilitas -->
                        <x-mary-menu-item title="Fasilitas" icon="o-building-office-2" :link="route('admin.facilities.index')"
                            :active="request()->routeIs('admin.facilities.*')" wire:navigate class="spa-nav-item" />

                        <!-- Gallery -->
                        <x-mary-menu-item title="Gallery" icon="o-photo" :link="route('admin.galleries.index')" :active="request()->routeIs('admin.galleries.*')"
                            wire:navigate class="spa-nav-item" />

                        <!-- Home Carousel -->
                        <x-mary-menu-item title="Carousel Beranda" icon="o-photo" :link="route('admin.home-carousels.index')" :active="request()->routeIs('admin.home-carousels.*')"
                            wire:navigate class="spa-nav-item" />

                        <!-- Contact Messages -->
                        <x-mary-menu-item title="Pesan Kontak" icon="o-envelope" :link="route('admin.contact-messages.index')" :active="request()->routeIs('admin.contact-messages.*')"
                            wire:navigate class="spa-nav-item" />
                    </div>

                    <!-- System Management Section -->
                    <div class="mt-4">
                        <x-mary-menu-title title="Manajemen Sistem" />

                        <!-- Users Management -->
                        <x-mary-menu-item title="Manajemen User" icon="o-users" :link="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                            wire:navigate class="spa-nav-item" />
                    </div>
                </x-mary-menu>
            </div>

            <!-- User Profile Section (Desktop Only) -->
            <div class="p-4 border-t border-base-300 mt-auto hidden lg:block">
                <x-mary-dropdown>
                    <x-slot:trigger>
                        <div class="btn btn-ghost w-full justify-start p-2 hover:bg-base-300 transition-all">
                            <div class="flex items-center gap-3 w-full">
                                <div
                                    class="bg-primary text-primary-content rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold flex-shrink-0">
                                    {{ auth()->user()->initials() }}
                                </div>
                                <div class="text-left min-w-0 flex-1">
                                    <div class="font-semibold text-sm truncate max-w-[120px]">{{ auth()->user()->nama }}
                                    </div>
                                    <div class="text-xs opacity-70 truncate max-w-[120px]">{{ auth()->user()->email }}
                                    </div>
                                </div>
                                <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </x-slot:trigger>

                    <!-- Dropdown Menu Items with SPA Navigation -->
                    <x-mary-menu-item title="Profile" icon="o-user" :link="route('admin.settings.profile')" wire:navigate
                        class="spa-nav-item" />

                    <x-mary-menu-item title="Password" icon="o-key" :link="route('admin.settings.password')" wire:navigate
                        class="spa-nav-item" />

                    <x-mary-menu-item title="Appearance" icon="o-paint-brush" :link="route('admin.settings.appearance')" wire:navigate
                        class="spa-nav-item" />

                    <x-mary-menu-separator />

                    <!-- Logout Form (No SPA) -->
                    <form method="POST" action="{{ route('logout') }}" class="no-spa">
                        @csrf
                        <x-mary-menu-item title="Logout" icon="o-arrow-right-on-rectangle"
                            onclick="this.closest('form').submit()" class="no-spa" />
                    </form>
                </x-mary-dropdown>
            </div>
        </aside>
    </div>

    <!-- Main Content Area -->
    <div class="drawer-content flex flex-col min-h-screen">
        <!-- Enhanced Mobile Header -->
        <div class="navbar bg-base-200 shadow-sm lg:hidden sticky top-0 z-30">
            <div class="flex-none">
                <label for="drawer-toggle" class="btn btn-square btn-ghost">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </label>
            </div>
            <div class="flex-1">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost text-xl transition-all hover:scale-105"
                    wire:navigate>
                    <x-app-logo />
                </a>
            </div>
            <!-- Mobile Profile Dropdown (Kanan Atas) -->
            <div class="flex-none">
                <x-mary-dropdown>
                    <x-slot:trigger>
                        <x-mary-button class="btn-ghost btn-circle">
                            <div
                                class="bg-primary text-primary-content rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                                {{ auth()->user()->initials() }}
                            </div>
                        </x-mary-button>
                    </x-slot:trigger>

                    <!-- Mobile User Info & Settings -->
                    <div class="p-4 min-w-[200px]">
                        <div class="text-sm font-semibold truncate">{{ auth()->user()->nama }}</div>
                        <div class="text-xs opacity-70 truncate">{{ auth()->user()->email }}</div>
                    </div>
                    <x-mary-menu-separator />

                    <!-- User Settings -->
                    <x-mary-menu-item title="Profile" icon="o-user" :link="route('admin.settings.profile')" wire:navigate
                        class="spa-nav-item" />

                    <x-mary-menu-item title="Password" icon="o-key" :link="route('admin.settings.password')" wire:navigate
                        class="spa-nav-item" />

                    <x-mary-menu-item title="Appearance" icon="o-paint-brush" :link="route('admin.settings.appearance')" wire:navigate
                        class="spa-nav-item" />

                    <x-mary-menu-separator />

                    <!-- Logout Form (No SPA) -->
                    <form method="POST" action="{{ route('logout') }}" class="no-spa">
                        @csrf
                        <x-mary-menu-item title="Logout" icon="o-arrow-right-on-rectangle"
                            onclick="this.closest('form').submit()" class="no-spa" />
                    </form>
                </x-mary-dropdown>
            </div>
        </div>

        <!-- Main Content with SPA transitions -->
        <main class="flex-1 p-4 lg:p-6 bg-base-100 transition-all duration-200">
            {{ $slot }}
        </main>
    </div>
</div>
