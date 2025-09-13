@php
    $siteSetting = \App\Models\SiteSetting::getInstance();
@endphp

<!-- Modern Frontend Footer dengan MaryUI -->
<footer class="bg-base-200 text-base-content border-t border-base-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            <!-- Logo & Deskripsi -->
            <div class="col-span-1 lg:col-span-2">
                <div class="flex items-center gap-3 mb-4">
                    @if($siteSetting->logo_url)
                        <img src="{{ $siteSetting->logo_url }}" alt="Logo {{ $siteSetting->nama_sekolah }}" class="w-10 h-10 object-contain">
                    @else
                        <x-app-logo class="w-10 h-10" />
                    @endif
                    <h3 class="text-xl font-bold" style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $siteSetting->nama_singkat ?: $siteSetting->nama_sekolah }}
                    </h3>
                </div>
                <p class="text-base-content/70 mb-4 max-w-md" style="font-family: 'Inter', sans-serif;">
                    {{ $siteSetting->deskripsi ?: 'Sekolah Menengah Kejuruan yang berkomitmen menghasilkan lulusan berkualitas, berkarakter, dan siap menghadapi tantangan dunia kerja modern.' }}
                </p>

                <!-- Social Media -->
                <div class="flex gap-3">
                    @if($siteSetting->instagram)
                        <a href="{{ str_starts_with($siteSetting->instagram, 'http') ? $siteSetting->instagram : 'https://instagram.com/' . ltrim($siteSetting->instagram, '@') }}" 
                           target="_blank" class="btn btn-circle btn-ghost btn-sm hover:btn-primary transition-all" title="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                            </svg>
                        </a>
                    @endif
                    
                    @if($siteSetting->facebook)
                        <a href="{{ str_starts_with($siteSetting->facebook, 'http') ? $siteSetting->facebook : 'https://facebook.com/' . $siteSetting->facebook }}" 
                           target="_blank" class="btn btn-circle btn-ghost btn-sm hover:btn-primary transition-all" title="Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    @endif
                    
                    @if($siteSetting->youtube)
                        <a href="{{ str_starts_with($siteSetting->youtube, 'http') ? $siteSetting->youtube : 'https://youtube.com/c/' . $siteSetting->youtube }}" 
                           target="_blank" class="btn btn-circle btn-ghost btn-sm hover:btn-primary transition-all" title="YouTube">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    @endif
                    
                    @if($siteSetting->tiktok)
                        <a href="{{ str_starts_with($siteSetting->tiktok, 'http') ? $siteSetting->tiktok : 'https://tiktok.com/' . ltrim($siteSetting->tiktok, '@') }}" 
                           target="_blank" class="btn btn-circle btn-ghost btn-sm hover:btn-primary transition-all" title="TikTok">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold text-lg mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Menu Utama
                </h4>
                <ul class="space-y-2" style="font-family: 'Inter', sans-serif;">
                    <li><a href="{{ route('home') }}"
                            class="text-base-content/70 hover:text-primary transition-colors">Beranda</a></li>
                    <li><a href="#tentang" class="text-base-content/70 hover:text-primary transition-colors">Tentang
                            Kami</a></li>
                    <li><a href="#program" class="text-base-content/70 hover:text-primary transition-colors">Program
                            Studi</a></li>
                    <li><a href="#fasilitas"
                            class="text-base-content/70 hover:text-primary transition-colors">Fasilitas</a></li>
                    <li><a href="#kontak" class="text-base-content/70 hover:text-primary transition-colors">Kontak</a>
                    </li>
                </ul>
            </div>

            <!-- Kontak Info -->
            <div>
                <h4 class="font-semibold text-lg mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Kontak Kami
                </h4>
                <ul class="space-y-3" style="font-family: 'Inter', sans-serif;">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 mt-0.5 text-primary flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-base-content/70 text-sm leading-relaxed">
                            {{ $siteSetting->alamat }}
                        </span>
                    </li>
                    @if($siteSetting->telepon)
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                            <a href="tel:{{ $siteSetting->telepon }}" class="text-base-content/70 text-sm hover:text-primary transition-colors">
                                {{ $siteSetting->telepon }}
                            </a>
                        </li>
                    @endif
                    @if($siteSetting->email)
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <a href="mailto:{{ $siteSetting->email }}" class="text-base-content/70 text-sm hover:text-primary transition-colors">
                                {{ $siteSetting->email }}
                            </a>
                        </li>
                    @endif
                    @if($siteSetting->website)
                        <li class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                </path>
                            </svg>
                            <a href="{{ $siteSetting->website }}" target="_blank" class="text-base-content/70 text-sm hover:text-primary transition-colors">
                                {{ str_replace(['https://', 'http://'], '', $siteSetting->website) }}
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="divider my-8"></div>
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-base-content/60 text-sm" style="font-family: 'Inter', sans-serif;">
                © {{ date('Y') }} {{ $siteSetting->nama_sekolah }}. Semua hak cipta dilindungi.
            </p>
            <div class="flex gap-6 text-sm" style="font-family: 'Inter', sans-serif;">
                <a href="#" class="text-base-content/60 hover:text-primary transition-colors">Kebijakan
                    Privasi</a>
                <a href="#" class="text-base-content/60 hover:text-primary transition-colors">Syarat &
                    Ketentuan</a>
                <a href="#" class="text-base-content/60 hover:text-primary transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
