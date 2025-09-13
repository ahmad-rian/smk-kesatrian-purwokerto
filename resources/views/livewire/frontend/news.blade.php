<div class="min-h-screen bg-base-100">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-[#000080] to-[#00004d] text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                Berita Terbaru
            </h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-2xl mx-auto" style="font-family: 'Inter', sans-serif;">
                Ikuti perkembangan dan informasi terbaru dari SMK Kesatrian Purwokerto
            </p>
        </div>
    </div>


    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Search Section -->
        <div class="mb-8">
            <div class="max-w-md mx-auto">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari berita..."
                        class="input input-bordered w-full pl-12 pr-4">
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
                        class="bg-base-100 rounded-lg shadow-sm border border-base-300 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                        <!-- Image -->
                        <div class="relative h-48 bg-base-200 overflow-hidden">
                            @if ($news->gambar)
                                <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/20 to-secondary/20">
                                    <svg class="w-16 h-16 text-base-content/30" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="badge badge-primary badge-sm">{{ ucfirst($news->status) }}</span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Date -->
                            <div class="flex items-center gap-2 text-sm text-base-content/60 mb-3">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>{{ $this->getFormattedDate($news->created_at) }}</span>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl font-bold mb-3 group-hover:text-primary transition-colors line-clamp-2"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                {{ $news->judul }}
                            </h3>

                            <!-- Excerpt -->
                            <p class="text-base-content/70 mb-4 line-clamp-3" style="font-family: 'Inter', sans-serif;">
                                {{ $this->getExcerpt($news->konten) }}
                            </p>

                            <!-- Read More Button -->
                            <a href="{{ route('berita.detail', $news->slug) }}" wire:navigate
                                class="btn btn-primary btn-sm group-hover:btn-secondary transition-colors">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $newsList->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 mx-auto text-base-content/30 mb-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>

                    @if ($search)
                        <h3 class="text-2xl font-bold text-base-content mb-4"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Tidak Ada Hasil
                        </h3>
                        <p class="text-base-content/60 mb-6" style="font-family: 'Inter', sans-serif;">
                            Tidak ditemukan berita dengan kata kunci "{{ $search }}". Coba gunakan kata kunci
                            lain.
                        </p>
                        <button wire:click="$set('search', '')" class="btn btn-primary">
                            Hapus Pencarian
                        </button>
                    @else
                        <h3 class="text-2xl font-bold text-base-content mb-4"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Belum Ada Berita
                        </h3>
                        <p class="text-base-content/60 mb-6" style="font-family: 'Inter', sans-serif;">
                            Saat ini belum ada berita yang dipublikasikan. Silakan kembali lagi nanti.
                        </p>
                        <a href="{{ route('home') }}" wire:navigate class="btn btn-primary">
                            Kembali ke Beranda
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
