<div class="min-h-screen bg-base-100">
    @if($activity)
        <!-- Hero Section dengan Gambar Kegiatan -->
        <section class="relative h-64 md:h-80 lg:h-96 overflow-hidden">
            @if($activity->gambar)
                <img src="{{ Storage::url($activity->gambar) }}" 
                     alt="{{ $activity->nama_kegiatan }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-r from-primary/20 to-secondary/20 flex items-center justify-center">
                    <div class="text-center">
                        <svg class="w-20 h-20 mx-auto text-primary/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-primary/60" style="font-family: 'Inter', sans-serif;">Foto Kegiatan</p>
                    </div>
                </div>
            @endif
            
            <!-- Overlay dengan gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            
            <!-- Breadcrumb -->
            <div class="absolute top-4 left-4 z-10">
                <div class="breadcrumbs text-sm text-white/90">
                    <ul style="font-family: 'Inter', sans-serif;">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors" wire:navigate>Beranda</a></li>
                        <li><a href="{{ route('kegiatan') }}" class="hover:text-white transition-colors" wire:navigate>Kegiatan</a></li>
                        <li class="text-white/70">{{ Str::limit($activity->nama_kegiatan, 30) }}</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Header Kegiatan -->
                    <div class="mb-8">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            @if($activity->kategori)
                                <span class="badge badge-primary badge-lg" style="font-family: 'Inter', sans-serif;">
                                    {{ $activity->kategori }}
                                </span>
                            @endif
                            <span class="text-base-content/60 text-sm" style="font-family: 'Inter', sans-serif;">
                                {{ $activity->created_at->format('d F Y') }}
                            </span>
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl font-bold text-base-content mb-4" 
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            {{ $activity->nama_kegiatan }}
                        </h1>
                        
                        @if($activity->tanggal_kegiatan)
                            <div class="flex items-center gap-2 text-base-content/70 mb-4">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span style="font-family: 'Inter', sans-serif;">
                                    {{ \Carbon\Carbon::parse($activity->tanggal_kegiatan)->format('d F Y') }}
                                </span>
                            </div>
                        @endif
                        
                        @if($activity->lokasi)
                            <div class="flex items-center gap-2 text-base-content/70">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span style="font-family: 'Inter', sans-serif;">{{ $activity->lokasi }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Deskripsi Kegiatan -->
                    @if($activity->deskripsi)
                        <div class="card bg-base-100 shadow-lg mb-8">
                            <div class="card-body">
                                <h2 class="card-title text-xl mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    Tentang Kegiatan
                                </h2>
                                <div class="prose max-w-none" style="font-family: 'Inter', sans-serif;">
                                    {!! nl2br(e($activity->deskripsi)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Keterangan Tambahan -->
                    @if($activity->keterangan)
                        <div class="card bg-base-100 shadow-lg">
                            <div class="card-body">
                                <h2 class="card-title text-xl mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    Informasi Tambahan
                                </h2>
                                <div class="prose max-w-none" style="font-family: 'Inter', sans-serif;">
                                    {!! nl2br(e($activity->keterangan)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Kegiatan Terkait -->
                    @if($relatedActivities->count() > 0)
                        <div class="card bg-base-100 shadow-lg mb-6">
                            <div class="card-body">
                                <h3 class="card-title text-lg mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    Kegiatan Terkait
                                </h3>
                                <div class="space-y-4">
                                    @foreach($relatedActivities as $related)
                                        <div class="flex gap-3 p-3 rounded-lg hover:bg-base-200 transition-colors cursor-pointer"
                                             onclick="window.location.href='{{ route('activity.detail', $related->id) }}'">
                                            @if($related->gambar)
                                                <img src="{{ Storage::url($related->gambar) }}" 
                                                     alt="{{ $related->nama_kegiatan }}"
                                                     class="w-16 h-16 object-cover rounded-lg flex-shrink-0">
                                            @else
                                                <div class="w-16 h-16 bg-base-300 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-6 h-6 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-sm line-clamp-2 mb-1" style="font-family: 'Inter', sans-serif;">
                                                    {{ $related->nama_kegiatan }}
                                                </h4>
                                                <p class="text-xs text-base-content/60" style="font-family: 'Inter', sans-serif;">
                                                    {{ $related->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tombol Kembali -->
                    <div class="card bg-base-100 shadow-lg">
                        <div class="card-body">
                            <a href="{{ route('kegiatan') }}" 
                               class="btn btn-outline btn-primary w-full" 
                               style="font-family: 'Inter', sans-serif;"
                               wire:navigate>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Kegiatan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        <!-- Error State - Kegiatan Tidak Ditemukan -->
        <section class="container mx-auto px-4 py-16 text-center">
            <div class="max-w-md mx-auto">
                <svg class="w-24 h-24 mx-auto text-base-content/20 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.007-5.824-2.709M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <h1 class="text-2xl font-bold text-base-content mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Kegiatan Tidak Ditemukan
                </h1>
                <p class="text-base-content/60 mb-6" style="font-family: 'Inter', sans-serif;">
                    Maaf, kegiatan yang Anda cari tidak dapat ditemukan atau mungkin telah dihapus.
                </p>
                <a href="{{ route('home') }}" class="btn btn-primary" style="font-family: 'Inter', sans-serif;" wire:navigate>
                    Kembali ke Beranda
                </a>
            </div>
        </section>
    @endif
</div>