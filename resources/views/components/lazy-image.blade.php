{{-- 
    Komponen Lazy Image dengan SEO Optimization
    
    Props:
    - src: URL gambar utama
    - alt: Alt text (auto-generated jika kosong)
    - webpSrc: URL gambar WebP (opsional)
    - class: Class CSS tambahan
    - context: Konteks untuk auto alt text
    - title: Judul gambar
    - lazy: Enable lazy loading (default: true)
    - placeholder: URL placeholder image
    
    @author Laravel Expert Agent
    @version 1.0
--}}

@php
    $attributes = $getLazyAttributes();
    $structuredData = $getStructuredData();
@endphp

{{-- Structured Data untuk SEO --}}
@if(!empty($structuredData))
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif

{{-- Picture Element dengan WebP Support --}}
@if($hasWebpSupport())
<picture>
    <source srcset="{{ $webpSrc }}" type="image/webp">
    <img 
        @foreach($attributes as $key => $value)
            {{ $key }}="{{ $value }}"
        @endforeach
    >
</picture>
@else
{{-- Regular Image --}}
<img 
    @foreach($attributes as $key => $value)
        {{ $key }}="{{ $value }}"
    @endforeach
>
@endif

{{-- Lazy Loading Script (jika diperlukan) --}}
@if($lazy && $placeholder)
@push('scripts')
<script>
// Intersection Observer untuk lazy loading
if ('IntersectionObserver' in window) {
    const lazyImages = document.querySelectorAll('.lazy-load');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy-load');
                img.classList.add('lazy-loaded');
                imageObserver.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
} else {
    // Fallback untuk browser lama
    const lazyImages = document.querySelectorAll('.lazy-load');
    lazyImages.forEach(img => {
        img.src = img.dataset.src;
        img.classList.remove('lazy-load');
        img.classList.add('lazy-loaded');
    });
}
</script>
@endpush
@endif

{{-- CSS untuk lazy loading effects --}}
@push('styles')
<style>
.lazy-load {
    opacity: 0.5;
    transition: opacity 0.3s ease-in-out;
}

.lazy-loaded {
    opacity: 1;
}

.img-responsive {
    max-width: 100%;
    height: auto;
    display: block;
}

/* Placeholder blur effect */
.lazy-load[data-src] {
    filter: blur(5px);
    transition: filter 0.3s ease-in-out, opacity 0.3s ease-in-out;
}

.lazy-loaded {
    filter: blur(0);
}
</style>
@endpush