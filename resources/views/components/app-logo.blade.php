@props([
    'size' => 'md', // xs, sm, md, lg, xl
    'showText' => true,
    'textPosition' => 'right', // right, bottom
    'class' => '',
    'logoClass' => '',
    'textClass' => '',
])

@php
    // Ambil data site setting untuk logo
    $siteSetting = \App\Models\SiteSetting::getInstance();

    // Definisi ukuran logo
    $sizes = [
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16',
    ];

    // Definisi ukuran text
    $textSizes = [
        'xs' => 'text-xs',
        'sm' => 'text-sm',
        'md' => 'text-base',
        'lg' => 'text-lg',
        'xl' => 'text-xl',
    ];

    $logoSize = $sizes[$size] ?? $sizes['md'];
    $textSize = $textSizes[$size] ?? $textSizes['md'];

    // Layout classes berdasarkan posisi text
    $containerClass = $textPosition === 'bottom' ? 'flex flex-col items-center' : 'flex items-center';
    $spacingClass = $textPosition === 'bottom' ? 'space-y-1' : 'space-x-2';
@endphp

<div {{ $attributes->merge(['class' => "$containerClass $spacingClass $class"]) }}>
    {{-- Logo Image atau Fallback --}}
    <div class="{{ $logoSize }} {{ $logoClass }} flex-shrink-0">
        @if ($siteSetting->logo && $siteSetting->logo_url)
            {{-- Logo dari Database --}}
            <img src="{{ $siteSetting->logo_url }}" alt="{{ $siteSetting->nama_sekolah ?? 'Logo Sekolah' }}"
                class="w-full h-full object-contain"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            {{-- Fallback Icon jika gambar gagal load --}}
            <div class="w-full h-full bg-primary rounded-xl flex items-center justify-center shadow-lg"
                style="display: none;">
                <x-mary-icon name="o-academic-cap" class="w-3/4 h-3/4 text-primary-content" />
            </div>
        @else
            {{-- Default Fallback Icon --}}
            <div class="w-full h-full bg-primary rounded-xl flex items-center justify-center shadow-lg">
                <x-mary-icon name="o-academic-cap" class="w-3/4 h-3/4 text-primary-content" />
            </div>
        @endif
    </div>

    {{-- Text Logo --}}
    @if ($showText)
        <div class="{{ $textClass }} min-w-0">
            @if ($textPosition === 'bottom')
                {{-- Text di bawah logo (center aligned) --}}
                <div class="text-center">
                    <h1 class="{{ $textSize }} font-bold text-base-content leading-tight truncate"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $siteSetting->nama_singkat ?? ($siteSetting->nama_sekolah ?? 'SMK Kesatrian') }}
                    </h1>
                    @if ($size !== 'xs' && $size !== 'sm')
                        <p class="text-xs text-base-content/70 leading-tight truncate"
                            style="font-family: 'Inter', sans-serif;">
                            {{ $siteSetting->tagline ?? 'Purwokerto, Jawa Tengah' }}
                        </p>
                    @endif
                </div>
            @else
                {{-- Text di samping logo (default) --}}
                <div class="flex flex-col min-w-0">
                    <h1 class="{{ $textSize }} font-bold text-base-content leading-tight truncate"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $siteSetting->nama_singkat ?? ($siteSetting->nama_sekolah ?? 'SMK Kesatrian') }}
                    </h1>
                    @if ($size !== 'xs' && $size !== 'sm')
                        <p class="text-xs text-base-content/70 leading-tight truncate"
                            style="font-family: 'Inter', sans-serif;">
                            {{ $siteSetting->tagline ?? 'Purwokerto, Jawa Tengah' }}
                        </p>
                    @endif
                </div>
            @endif
        </div>
    @endif
</div>
