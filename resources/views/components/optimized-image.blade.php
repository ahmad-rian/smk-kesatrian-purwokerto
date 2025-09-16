@php
    $dimensions = $getDimensions();
    $altText = $getAltText();
    $titleText = $getTitleText();
    $webpSrcset = $getWebpSrcset();
    $srcset = $getSrcset();
    $placeholder = $getPlaceholder();
@endphp

{{-- Picture element with WebP support --}}
<picture class="optimized-image-wrapper {{ $class }}" 
         x-data="optimizedImage" 
         x-init="init($el)"
         data-alt="{{ $altText }}"
         data-title="{{ $titleText }}">
    
    {{-- WebP source if available --}}
    @if($webpSrcset)
        <source 
            srcset="{{ $lazy ? $placeholder : $webpSrcset }}" 
            data-srcset="{{ $webpSrcset }}"
            sizes="{{ $sizes }}"
            type="image/webp"
            class="{{ $lazy ? 'lazy' : '' }}"
        >
    @endif
    
    {{-- Fallback image --}}
    <img 
        src="{{ $lazy ? $placeholder : $src }}"
        @if($lazy)
            data-src="{{ $src }}"
            @if($srcset !== $src)
                data-srcset="{{ $srcset }}"
            @endif
        @else
            @if($srcset !== $src)
                srcset="{{ $srcset }}"
            @endif
        @endif
        alt="{{ $altText }}"
        title="{{ $titleText }}"
        @if($dimensions['width'])
            width="{{ $dimensions['width'] }}"
        @endif
        @if($dimensions['height'])
            height="{{ $dimensions['height'] }}"
        @endif
        sizes="{{ $sizes }}"
        loading="{{ $loading }}"
        decoding="{{ $decoding }}"
        class="optimized-image {{ $lazy ? 'lazy opacity-0 transition-opacity duration-300' : '' }} {{ $class }}"
        style="{{ $lazy ? 'transition: opacity 0.3s ease-in-out;' : '' }}"
        onerror="this.onerror=null; this.src='{{ $placeholder }}'; this.classList.add('error');"
    >
    
    {{-- Loading indicator --}}
    @if($lazy)
        <div class="image-loading-indicator absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded">
            <div class="flex flex-col items-center space-y-2">
                <svg class="animate-spin h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-xs text-gray-500 dark:text-gray-400">Loading...</span>
            </div>
        </div>
    @endif
</picture>

{{-- Structured Data untuk SEO --}}
@if(!$lazy || !empty($context))
    <script type="application/ld+json">
        {!! json_encode($getStructuredData()) !!}
    </script>
@endif

{{-- Alpine.js Component --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('optimizedImage', () => ({
            observer: null,
            loaded: false,
            error: false,
            
            init(element) {
                const img = element.querySelector('img');
                const loadingIndicator = element.querySelector('.image-loading-indicator');
                
                // Skip if not lazy loading
                if (!img.classList.contains('lazy')) {
                    return;
                }
                
                // Create intersection observer for lazy loading
                this.observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.loadImage(img, loadingIndicator);
                            this.observer.unobserve(entry.target);
                        }
                    });
                }, {
                    rootMargin: '50px 0px',
                    threshold: 0.1
                });
                
                this.observer.observe(img);
                
                // Fallback for browsers without IntersectionObserver
                if (!('IntersectionObserver' in window)) {
                    this.loadImage(img, loadingIndicator);
                }
            },
            
            loadImage(img, loadingIndicator) {
                if (this.loaded) return;
                
                const dataSrc = img.getAttribute('data-src');
                const dataSrcset = img.getAttribute('data-srcset');
                
                if (!dataSrc) return;
                
                // Load image
                const tempImg = new Image();
                
                tempImg.onload = () => {
                    // Update src and srcset
                    img.src = dataSrc;
                    if (dataSrcset) {
                        img.srcset = dataSrcset;
                    }
                    
                    // Update WebP source if exists
                    const webpSource = img.parentElement.querySelector('source[type="image/webp"]');
                    if (webpSource) {
                        const webpSrcset = webpSource.getAttribute('data-srcset');
                        if (webpSrcset) {
                            webpSource.srcset = webpSrcset;
                        }
                    }
                    
                    // Show image with fade-in effect
                    img.classList.remove('opacity-0');
                    img.classList.add('opacity-100');
                    
                    // Hide loading indicator
                    if (loadingIndicator) {
                        loadingIndicator.style.display = 'none';
                    }
                    
                    this.loaded = true;
                    
                    // Dispatch loaded event
                    img.dispatchEvent(new CustomEvent('image:loaded', {
                        detail: { src: dataSrc }
                    }));
                };
                
                tempImg.onerror = () => {
                    // Show error state
                    img.classList.add('error');
                    img.alt = 'Image failed to load';
                    
                    // Hide loading indicator
                    if (loadingIndicator) {
                        loadingIndicator.innerHTML = '<span class="text-xs text-red-500">Failed to load</span>';
                    }
                    
                    this.error = true;
                    
                    // Dispatch error event
                    img.dispatchEvent(new CustomEvent('image:error', {
                        detail: { src: dataSrc }
                    }));
                };
                
                // Start loading
                tempImg.src = dataSrc;
            }
        }));
    });
</script>

{{-- CSS Styles --}}
<style>
    .optimized-image-wrapper {
        position: relative;
        display: inline-block;
        overflow: hidden;
    }
    
    .optimized-image {
        max-width: 100%;
        height: auto;
        display: block;
    }
    
    .optimized-image.lazy {
        min-height: 200px; /* Prevent layout shift */
    }
    
    .optimized-image.error {
        filter: grayscale(100%);
        opacity: 0.5;
    }
    
    .image-loading-indicator {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading-shimmer 1.5s infinite;
    }
    
    @keyframes loading-shimmer {
        0% {
            background-position: -200% 0;
        }
        100% {
            background-position: 200% 0;
        }
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .image-loading-indicator {
            background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
            background-size: 200% 100%;
        }
    }
    
    /* Responsive behavior */
    @media (max-width: 768px) {
        .optimized-image.lazy {
            min-height: 150px;
        }
    }
    
    /* Print styles */
    @media print {
        .image-loading-indicator {
            display: none;
        }
        
        .optimized-image.lazy {
            opacity: 1 !important;
        }
    }
    
    /* Accessibility improvements */
    @media (prefers-reduced-motion: reduce) {
        .optimized-image {
            transition: none !important;
        }
        
        .image-loading-indicator {
            animation: none;
        }
    }
    
    /* High contrast mode */
    @media (prefers-contrast: high) {
        .optimized-image.error {
            filter: none;
            border: 2px solid red;
        }
    }
</style>