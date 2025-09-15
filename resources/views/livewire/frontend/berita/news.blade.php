<div class="min-h-screen bg-base-100">
    <!-- Modern Hero Section with Animation -->
    <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white">
        <div class="container mx-auto px-4">
            <!-- Desktop Layout - Grid 2 kolom -->
            <div class="hidden lg:grid lg:grid-cols-5 min-h-[400px]">
                <!-- Left Side - Content -->
                <div class="lg:col-span-3 flex items-center justify-center py-20">
                    <div class="max-w-2xl">
                        <h1 class="text-4xl md:text-6xl font-bold mb-4"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Berita Terbaru
                        </h1>
                        <p class="text-xl md:text-2xl opacity-90 mb-6 leading-relaxed"
                            style="font-family: 'Inter', sans-serif;">
                            Ikuti perkembangan dan informasi terbaru dari SMK Kesatrian Purwokerto
                        </p>
                        <div class="flex flex-wrap items-center gap-4 text-white/80">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                                <span class="text-sm">Informasi Terkini</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-5 5v-5zM4 19h5v-5h5V9h5V4H4v15z">
                                    </path>
                                </svg>
                                <span class="text-sm">Update Sekolah</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Animation -->
                <div class="lg:col-span-2 flex items-center justify-center p-6">
                    <div class="text-center">
                        <!-- Lottie Animation -->
                        <div class="mb-4">
                            <div class="relative w-[300px] h-[300px] mx-auto">
                                <lottie-player src="/assets/animations/berita.json" background="transparent"
                                    speed="1"
                                    style="width: 100%; height: 100%; background: none !important; background-color: transparent !important;"
                                    loop autoplay renderer="svg" mode="normal">
                                </lottie-player>
                            </div>
                            <!-- Fallback jika animasi gagal -->
                            <div class="hidden animate-pulse" id="animation-fallback">
                                <div
                                    class="w-24 h-24 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Animation Text -->
                        <div class="text-white">
                            <h3 class="text-lg font-bold mb-2">Berita Terkini</h3>
                            <p class="text-sm opacity-80">Informasi terbaru dari sekolah</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile & Tablet Layout - Single Column -->
            <div class="lg:hidden py-16 text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Berita Terbaru
                </h1>
                <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto mb-6"
                    style="font-family: 'Inter', sans-serif;">
                    Ikuti perkembangan dan informasi terbaru dari SMK Kesatrian Purwokerto
                </p>

                <!-- Mobile Animation -->
                <div class="flex justify-center mb-6">
                    <div class="relative w-[200px] h-[200px]">
                        <lottie-player src="/assets/animations/berita.json" background="transparent" speed="1"
                            style="width: 100%; height: 100%; background: none !important; background-color: transparent !important;"
                            loop autoplay renderer="svg" mode="normal">
                        </lottie-player>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Search Section -->
        <div class="mb-8">
            <div class="max-w-md mx-auto">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berita..."
                        class="input input-bordered w-full pl-12 pr-4 focus:input-primary">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-base-content/40" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- News Grid -->
        @if ($newsList->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach ($newsList as $news)
                    <article
                        class="bg-base-100 rounded-xl shadow-sm border border-base-300 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <!-- Image -->
                        <div class="relative h-48 bg-base-200 overflow-hidden">
                            @if ($news->gambar)
                                <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100/50 to-slate-100/50">
                                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="badge bg-blue-600 text-white border-blue-600 badge-sm"
                                    style="font-family: 'Inter', sans-serif;">
                                    {{ ucfirst($news->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Date -->
                            <div class="flex items-center gap-2 text-xs text-base-content/60 mb-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span
                                    style="font-family: 'Inter', sans-serif;">{{ $this->getFormattedDate($news->created_at) }}</span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl font-bold mb-3 group-hover:text-blue-600 transition-colors line-clamp-2"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                {{ $news->judul }}
                            </h3>

                            <!-- Excerpt -->
                            <p class="text-base-content/70 text-sm mb-4 line-clamp-3 leading-relaxed"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $this->getExcerpt($news->konten) }}
                            </p>

                            <!-- Read More Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('berita.detail', $news->slug) }}" wire:navigate
                                    class="btn btn-primary btn-sm hover:scale-105 transition-transform duration-200"
                                    style="font-family: 'Inter', sans-serif;">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Baca Selengkapnya
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $newsList->links() }}
            </div>
        @else
            <!-- Enhanced Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="mb-6">
                        <div class="relative w-[200px] h-[200px] mx-auto">
                            <lottie-player src="/assets/animations/berita.json" background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%; background: none !important; background-color: transparent !important;"
                                loop autoplay renderer="svg" mode="normal">
                            </lottie-player>
                        </div>
                        <!-- Fallback untuk empty state -->
                        <svg class="w-24 h-24 mx-auto text-base-content/20 mb-6 hidden" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>

                    @if ($search)
                        <h3 class="text-2xl font-bold text-base-content mb-4"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Tidak Ada Hasil Ditemukan
                        </h3>
                        <p class="text-base-content/60 mb-6 leading-relaxed"
                            style="font-family: 'Inter', sans-serif;">
                            Tidak ditemukan berita dengan kata kunci "{{ $search }}". Coba gunakan kata kunci
                            lain.
                        </p>
                        <button wire:click="$set('search', '')" class="btn btn-primary"
                            style="font-family: 'Inter', sans-serif;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Hapus Pencarian
                        </button>
                    @else
                        <h3 class="text-2xl font-bold text-base-content mb-4"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Belum Ada Berita
                        </h3>
                        <p class="text-base-content/60 mb-6 leading-relaxed"
                            style="font-family: 'Inter', sans-serif;">
                            Saat ini belum ada berita yang dipublikasikan. Silakan kembali lagi nanti.
                        </p>
                        <a href="{{ route('home') }}" wire:navigate class="btn btn-primary"
                            style="font-family: 'Inter', sans-serif;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m0 0V11a1 1 0 011-1h2a1 1 0 011 1v10m0 0a1 1 0 001-1V10M5 10L12 3l7 7">
                                </path>
                            </svg>
                            Kembali ke Beranda
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
