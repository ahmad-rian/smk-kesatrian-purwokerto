<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">

<head>
    @include('partials.head')
    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-primary/5 to-secondary/5 antialiased">
    <!-- Main Content - Centered -->
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-4xl">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block" wire:navigate>
                    {{-- Logo Dinamis dari Database --}}
                    <x-app-logo size="xl" :showText="true" textPosition="bottom" logoClass="shadow-lg"
                        class="justify-center" />
                </a>
            </div>

            <!-- Main Content -->
            {{ $slot }}

            <!-- Footer -->
            <div class="mt-6 text-center">
                <p class="text-xs text-base-content/60">
                    © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. {{ __('All rights reserved.') }}
                </p>
            </div>
        </div>
    </div>
    @livewireScripts

    <!-- Script untuk menangani error Lottie animation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lottiePlayer = document.querySelector('lottie-player');
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
    </script>
</body>

</html>
