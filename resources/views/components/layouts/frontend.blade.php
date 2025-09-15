<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('appearance', 'light') }}">

<head>
    @include('partials.head')
    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Frontend Specific Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,600,700|inter:400,500,600"
        rel="stylesheet" />

    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- Alpine.js CSS untuk x-cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-base-100 text-base-content transition-colors duration-300">

    <!-- Frontend Navbar -->
    <x-layouts.frontend.navbar />

    <!-- Main Content -->
    <main class="min-h-screen pt-4">
        {{ $content ?? $slot }}
    </main>

    <!-- Frontend Footer -->
    <x-layouts.frontend.footer />

    <!-- Floating Components -->
    <x-scroll-to-top />
    <x-whatsapp-button />

    @vite(['resources/js/app.js'])
    @livewireScripts

    <!-- Script untuk menangani error Lottie animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle multiple lottie players on the page
            const lottiePlayers = document.querySelectorAll('lottie-player');

            lottiePlayers.forEach((lottiePlayer, index) => {
                const fallback = document.getElementById('animation-fallback');

                if (lottiePlayer && fallback) {
                    // Timeout untuk fallback jika animasi tidak load dalam 5 detik
                    const timeout = setTimeout(() => {
                        lottiePlayer.style.display = 'none';
                        fallback.classList.remove('hidden');
                    }, 5000);

                    // Event listener untuk sukses load
                    lottiePlayer.addEventListener('ready', () => {
                        clearTimeout(timeout);
                    });

                    // Event listener untuk error
                    lottiePlayer.addEventListener('error', () => {
                        clearTimeout(timeout);
                        lottiePlayer.style.display = 'none';
                        fallback.classList.remove('hidden');
                    });
                }
            });
        });
    </script>
</body>

</html>
