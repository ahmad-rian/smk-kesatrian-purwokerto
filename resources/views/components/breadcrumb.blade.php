{{-- Breadcrumb Component dengan Schema Markup --}}
@if($shouldShow())
    {{-- Structured Data untuk SEO --}}
    @if($showStructuredData && $getStructuredData())
        <script type="application/ld+json">
            {!! $getStructuredData() !!}
        </script>
    @endif

    {{-- Breadcrumb Navigation --}}
    <nav aria-label="Breadcrumb" class="{{ $containerClass }}" role="navigation">
        <div class="flex items-center space-x-1 text-sm text-gray-600 dark:text-gray-400">
            @foreach($items as $index => $item)
                {{-- Breadcrumb Item --}}
                <div class="flex items-center">
                    @if(!$loop->first)
                        {{-- Separator --}}
                        <x-mary-icon 
                            :name="$separator" 
                            class="w-4 h-4 mx-2 text-gray-400 dark:text-gray-500" 
                        />
                    @endif
                    
                    {{-- Breadcrumb Link atau Text --}}
                    @if($item['active'])
                        {{-- Current Page (tidak clickable) --}}
                        <span class="flex items-center font-medium text-gray-900 dark:text-gray-100" 
                              aria-current="page">
                            @if($item['icon'])
                                <x-mary-icon 
                                    :name="$item['icon']" 
                                    class="w-4 h-4 mr-1.5" 
                                />
                            @endif
                            {{ $item['label'] }}
                        </span>
                    @else
                        {{-- Clickable Link --}}
                        <a href="{{ $item['url'] }}" 
                           class="flex items-center text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors duration-200"
                           title="Kembali ke {{ $item['label'] }}">
                            @if($item['icon'])
                                <x-mary-icon 
                                    :name="$item['icon']" 
                                    class="w-4 h-4 mr-1.5" 
                                />
                            @endif
                            {{ $item['label'] }}
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </nav>

    {{-- Mobile Breadcrumb (Simplified) --}}
    <nav aria-label="Mobile Breadcrumb" class="md:hidden mb-4" role="navigation">
        @php
            $parentPage = $getParentPage();
            $currentTitle = $getCurrentPageTitle();
        @endphp
        
        @if($parentPage && $currentTitle)
            <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                {{-- Back Button --}}
                <a href="{{ $parentPage['url'] }}" 
                   class="flex items-center text-sm text-gray-600 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                    <x-mary-icon name="o-chevron-left" class="w-4 h-4 mr-1" />
                    {{ $parentPage['label'] }}
                </a>
                
                {{-- Current Page --}}
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ $currentTitle }}
                </span>
            </div>
        @endif
    </nav>
@endif

{{-- Breadcrumb Styles --}}
<style>
    /* Custom breadcrumb styles */
    .breadcrumbs {
        @apply mb-6;
    }
    
    .breadcrumbs a:hover {
        text-decoration: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 640px) {
        .breadcrumbs {
            @apply hidden;
        }
    }
    
    /* Dark mode adjustments */
    @media (prefers-color-scheme: dark) {
        .breadcrumbs {
            color-scheme: dark;
        }
    }
    
    /* Print styles */
    @media print {
        .breadcrumbs {
            @apply hidden;
        }
    }
    
    /* High contrast mode */
    @media (prefers-contrast: high) {
        .breadcrumbs a {
            text-decoration: underline;
        }
    }
    
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
        .breadcrumbs a {
            transition: none;
        }
    }
</style>

{{-- Microdata untuk SEO tambahan --}}
<div itemscope itemtype="https://schema.org/BreadcrumbList" style="display: none;">
    @foreach($items as $index => $item)
        <div itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <meta itemprop="position" content="{{ $index + 1 }}" />
            @if($item['url'] && !$item['active'])
                <a itemprop="item" href="{{ $item['url'] }}">
                    <span itemprop="name">{{ $item['label'] }}</span>
                </a>
            @else
                <span itemprop="name">{{ $item['label'] }}</span>
            @endif
        </div>
    @endforeach
</div>