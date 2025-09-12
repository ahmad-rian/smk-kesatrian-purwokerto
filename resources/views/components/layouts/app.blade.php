<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('appearance', 'light') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <!-- Alpine.js CSS untuk x-cloak -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* SPA Loading Animation */
        .spa-loading {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, hsl(var(--p)), transparent);
            z-index: 9999;
            transform: translateX(-100%);
            animation: loading 1s infinite;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .spa-loading.active {
            opacity: 1;
        }

        @keyframes loading {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Page Transition */
        .page-transition {
            transition: opacity 0.15s ease-in-out, transform 0.15s ease-in-out;
        }

        .page-transition.loading {
            opacity: 0.8;
            transform: translateY(2px);
        }

        /* Smooth navigation states */
        [wire\:navigate] {
            transition: all 0.1s ease;
        }

        [wire\:navigate]:hover {
            transform: translateX(2px);
        }

        /* Enhanced navigation item animations */
        .spa-nav-item {
            transition: all 0.2s ease;
        }

        .spa-nav-item:hover {
            transform: translateX(4px);
            background: rgba(var(--primary), 0.1);
        }

        .spa-nav-item.active {
            background: rgba(var(--primary), 0.2);
            border-right: 3px solid hsl(var(--primary));
        }
    </style>

</head>

<body class="font-sans antialiased bg-base-100">

        <!-- SPA Loading Indicator -->
        <div id="spa-loading" class="spa-loading" style="display: none;"></div>

        <!-- Page Content with SPA transitions -->
        <div id="page-content" class="page-transition">
            {{ $slot }}
        </div>

        <!-- MaryUI Toast Component -->
        <x-mary-toast />

        <!-- Scripts -->
        @livewireScripts

        <!-- SPA Navigation Script -->
        <script>
            // Initialize SPA only once
            if (!window.spaInitialized) {
                // Global SPA variables
                window.spaState = {
                    loading: false,
                    transition: false,
                    timeout: null,
                    errorCount: 0
                };

                // SPA UI Elements - declare in window scope
                window.spaElements = {
                    loading: null,
                    content: null
                };

                // Initialize SPA function
                window.initializeSPA = function() {
                    console.log('Initializing SPA navigation...');
                    
                    // Get UI elements and store in window scope
                    window.spaElements.loading = document.getElementById('spa-loading');
                    window.spaElements.content = document.getElementById('page-content');
                    
                    // Enhanced Livewire navigation events
                document.addEventListener('livewire:navigating', () => {
                    console.log('SPA Navigation starting...');
                    window.spaState.loading = true;
                    window.spaState.transition = true;
                    window.spaState.errorCount = 0;

                    // Show loading indicator
                    if (window.spaElements.loading) {
                        window.spaElements.loading.style.display = 'block';
                        window.spaElements.loading.classList.add('active');
                    }
                    
                    // Add loading class to page content
                    if (window.spaElements.content) {
                        window.spaElements.content.classList.add('loading');
                    }

                    // Clear any existing timeout
                    if (window.spaState.timeout) {
                        clearTimeout(window.spaState.timeout);
                    }
                });

                document.addEventListener('livewire:navigated', () => {
                    console.log('SPA Navigation completed');

                    // Close mobile drawer after navigation
                    const drawerToggle = document.getElementById('drawer-toggle');
                    if (drawerToggle && window.innerWidth < 1024) {
                        drawerToggle.checked = false;
                    }

                    // Small delay for smooth transition
                    const timeout = setTimeout(() => {
                        window.spaState.loading = false;
                        window.spaState.transition = false;
                        
                        // Hide loading indicator
                        if (window.spaElements.loading) {
                            window.spaElements.loading.style.display = 'none';
                            window.spaElements.loading.classList.remove('active');
                        }
                        
                        // Remove loading class from page content
                        if (window.spaElements.content) {
                            window.spaElements.content.classList.remove('loading');
                        }
                    }, 150);
                    
                    window.spaState.timeout = timeout;
                });

                // Enhanced preloading on hover
                document.addEventListener('mouseover', (e) => {
                    const link = e.target.closest('a[wire\\:navigate], a[href^="/admin"]');
                    if (link && link.href && !link.dataset.preloaded && 
                        link.href.startsWith(window.location.origin) && 
                        !link.classList.contains('no-spa')) {
                        
                        link.dataset.preloaded = 'true';
                        console.log('Preloading:', link.href);
                        
                        // Preload the page
                        const preloadLink = document.createElement('link');
                        preloadLink.rel = 'prefetch';
                        preloadLink.href = link.href;
                        document.head.appendChild(preloadLink);
                    }
                });

                // Fixed error handling - prevent infinite loops
                document.addEventListener('livewire:navigation-failed', (e) => {
                    console.error('SPA Navigation failed:', e);
                    window.spaState.loading = false;
                    window.spaState.transition = false;
                    window.spaState.errorCount++;
                    
                    // Hide loading indicator
                    if (window.spaElements.loading) {
                        window.spaElements.loading.style.display = 'none';
                        window.spaElements.loading.classList.remove('active');
                    }
                    
                    // Remove loading class from page content
                    if (window.spaElements.content) {
                        window.spaElements.content.classList.remove('loading');
                    }

                    // Only fallback if error count is low and we have a valid URL
                    if (window.spaState.errorCount <= 2 && e.detail && e.detail.url && 
                        e.detail.url !== window.location.href) {
                        console.log('Attempting fallback to regular navigation:', e.detail.url);
                        setTimeout(() => {
                            window.location.href = e.detail.url;
                        }, 100);
                    } else {
                        console.log('SPA navigation failed, staying on current page');
                    }
                });
                
                    console.log('SPA navigation initialized successfully');
                }; // End of initializeSPA function
            
            // Mark SPA as initialized
            window.spaInitialized = true;
        }
        </script>

        <!-- Theme Management Script -->
        <script>
            // Global theme management
            window.currentAppearance = @js(session('appearance', 'light'));

            // Theme application function
            window.applyTheme = function(appearance) {
                const html = document.documentElement;
                console.log('Applying theme:', appearance);

                if (appearance === 'system') {
                    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const theme = systemPrefersDark ? 'dark' : 'light';
                    html.setAttribute('data-theme', theme);
                    console.log('System theme applied:', theme);
                } else {
                    html.setAttribute('data-theme', appearance);
                    console.log('Theme applied:', appearance);
                }

                window.currentAppearance = appearance;
            }

            // Single initialization point untuk menghindari duplikasi
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Initializing SPA and Theme...');

                // Initialize SPA navigation hanya sekali
                if (window.initializeSPA && !window.spaNavigationReady) {
                    initializeSPA();
                    window.spaNavigationReady = true;
                }

                // Apply current theme on load
                applyTheme(window.currentAppearance);

                console.log('SPA and Theme initialized successfully');
            });

            // Enhanced Livewire initialization
            document.addEventListener('livewire:init', () => {
                console.log('Livewire initialized for SPA');

                // Listen for appearance updates
                Livewire.on('appearance-updated', (event) => {
                    console.log('Livewire appearance-updated event:', event);
                    if (event.appearance || event[0]?.appearance) {
                        const newAppearance = event.appearance || event[0].appearance;
                        applyTheme(newAppearance);
                    }
                });

                // Listen for system theme changes
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                    if (window.currentAppearance === 'system') {
                        console.log('System theme changed, reapplying...');
                        applyTheme('system');
                    }
                });
            });

            // Debug helper function
            window.debugSPA = function() {
                console.log('SPA Debug Info:', {
                    currentTheme: document.documentElement.getAttribute('data-theme'),
                    currentAppearance: window.currentAppearance,
                    livewireVersion: window.Livewire?.version || 'Not loaded',
                    currentURL: window.location.href,
                    hasWireNavigate: !!window.Livewire?.navigate,
                    wireNavigateLinks: document.querySelectorAll('[wire\\:navigate]').length,
                    spaInitialized: window.spaInitialized,
                    spaNavigationReady: window.spaNavigationReady
                });
            }

            // Test SPA function
            window.testSPA = function() {
                console.log('Testing SPA navigation...');
                const profileLink = document.querySelector('a[href*="settings/profile"]');
                if (profileLink) {
                    console.log('Found profile link, testing navigation');
                    profileLink.click();
                } else {
                    console.log('No profile link found');
                }
            }
        </script>
</body>

</html>
