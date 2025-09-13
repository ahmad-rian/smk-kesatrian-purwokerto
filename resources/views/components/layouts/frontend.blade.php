<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('appearance', 'light') }}">

<head>
    @include('partials.head')
    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Frontend Specific Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,600,700|inter:400,500,600"
        rel="stylesheet" />

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
    <main class="min-h-screen pt-18">
        {{ $content ?? $slot }}
    </main>

    <!-- Frontend Footer -->
    <x-layouts.frontend.footer />

    @vite(['resources/js/app.js'])
    @livewireScripts
</body>

</html>
