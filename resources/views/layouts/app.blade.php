<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SMK Kesatrian'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Inter:wght@100..900&display=swap"
        rel="stylesheet">

    <!-- Favicon -->
    @php
        $siteSettings = \App\Models\SiteSetting::first();
    @endphp
    @if ($siteSettings && $siteSettings->logo)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($siteSettings->logo) }}">
    @else
        <link rel="icon" type="image/x-icon" href="/favicon.ico">
    @endif

    <!-- Meta Tags -->
    @if ($siteSettings)
        <meta name="description"
            content="{{ $siteSettings->deskripsi ?? ($siteSettings->tagline ?? 'SMK Kesatrian - Membangun Generasi Unggul') }}">
        <meta name="keywords"
            content="SMK, Sekolah Menengah Kejuruan, {{ $siteSettings->nama_sekolah ?? 'SMK Kesatrian' }}, Pendidikan, Teknologi">
        <meta name="author" content="{{ $siteSettings->nama_sekolah ?? 'SMK Kesatrian' }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="@yield('title', $siteSettings->nama_sekolah ?? 'SMK Kesatrian')">
        <meta property="og:description"
            content="{{ $siteSettings->deskripsi ?? ($siteSettings->tagline ?? 'SMK Kesatrian - Membangun Generasi Unggul') }}">
        @if ($siteSettings->logo)
            <meta property="og:image" content="{{ Storage::url($siteSettings->logo) }}">
        @endif

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url()->current() }}">
        <meta property="twitter:title" content="@yield('title', $siteSettings->nama_sekolah ?? 'SMK Kesatrian')">
        <meta property="twitter:description"
            content="{{ $siteSettings->deskripsi ?? ($siteSettings->tagline ?? 'SMK Kesatrian - Membangun Generasi Unggul') }}">
        @if ($siteSettings->logo)
            <meta property="twitter:image" content="{{ Storage::url($siteSettings->logo) }}">
        @endif
    @endif

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="font-sans antialiased bg-base-100 text-base-content" style="font-family: 'Inter', sans-serif;">
    <!-- Navigation Bar -->
    <nav class="navbar bg-base-100 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto">
            <div class="navbar-start">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if ($siteSettings)
                        <x-app-logo size="sm" :showText="true" textPosition="right" logoClass="shadow-sm"
                            class="gap-2" />
                    @else
                        <div class="text-xl font-bold" style="font-family: 'Bricolage Grotesque', sans-serif;">
                            SMK Kesatrian
                        </div>
                    @endif
                </a>
            </div>

            <div class="navbar-center hidden lg:flex">
                <ul class="menu menu-horizontal px-1 gap-2" style="font-family: 'Inter', sans-serif;">
                    <li><a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="#tentang" class="hover:text-primary transition-colors">Tentang</a></li>
                    <li><a href="#program" class="hover:text-primary transition-colors">Program Studi</a></li>
                    <li><a href="#kegiatan" class="hover:text-primary transition-colors">Kegiatan</a></li>
                    <li><a href="#fasilitas" class="hover:text-primary transition-colors">Fasilitas</a></li>
                    <li><a href="#kontak" class="hover:text-primary transition-colors">Kontak</a></li>
                </ul>
            </div>

            <div class="navbar-end">
                @auth
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full">
                                @if (auth()->user()->avatar)
                                    <img src="{{ Storage::url(auth()->user()->avatar) }}"
                                        alt="{{ auth()->user()->name }}" />
                                @else
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                        <span
                                            class="text-primary font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <ul tabindex="0"
                            class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('settings.profile') }}">Profile</a></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <div class="flex gap-2">
                        <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                        @endif
                    </div>
                @endauth

                <!-- Mobile menu button -->
                <div class="dropdown dropdown-end lg:hidden">
                    <div tabindex="0" role="button" class="btn btn-ghost">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>
                    <ul tabindex="0"
                        class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="#tentang">Tentang</a></li>
                        <li><a href="#program">Program Studi</a></li>
                        <li><a href="#kegiatan">Kegiatan</a></li>
                        <li><a href="#fasilitas">Fasilitas</a></li>
                        <li><a href="#kontak">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Scripts -->
    @livewireScripts

    <!-- Additional Scripts -->
    @stack('scripts')

    <!-- Smooth Scrolling Script -->
    <script>
        // Smooth scrolling untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>
