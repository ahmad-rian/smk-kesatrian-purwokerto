<div class="min-h-screen bg-base-100">
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-[#000080] to-[#00004d] text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                Kegiatan Sekolah
            </h1>
            <p class="text-xl md:text-2xl opacity-90 max-w-2xl mx-auto" style="font-family: 'Inter', sans-serif;">
                Berbagai kegiatan dan acara menarik di SMK Kesatrian Purwokerto
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-12">
        <!-- Search and Filter Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-center">
                <!-- Search Input -->
                <div class="relative max-w-md w-full">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kegiatan..."
                        class="input input-bordered w-full pl-12 pr-4">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-base-content/40" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                @if(count($categories) > 0)
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button wire:click="$set('selectedCategory', 'semua')"
                            class="btn btn-sm {{ $selectedCategory === 'semua' ? 'btn-primary' : 'btn-outline' }}">
                            Semua
                        </button>
                        @foreach($categories as $category)
                            <button wire:click="$set('selectedCategory', '{{ $category }}')"
                                class="btn btn-sm {{ $selectedCategory === $category ? 'btn-primary' : 'btn-outline' }}">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Activities Grid -->
        @if ($activities->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach ($activities as $activity)
                    <article
                        class="bg-base-100 rounded-lg shadow-sm border border-base-300 overflow-hidden hover:shadow-lg transition-all duration-300 group">
                        <!-- Image -->
                        <div class="relative h-48 bg-base-200 overflow-hidden">
                            @if ($activity->gambar)
                                <img src="{{ Storage::url($activity->gambar) }}" alt="{{ $activity->nama_kegiatan }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/20 to-secondary/20">
                                    <svg class="w-16 h-16 text-base-content/30" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            @if($activity->kategori)
                                <div class="absolute top-4 left-4">
                                    <span class="badge badge-primary badge-sm" style="font-family: 'Inter', sans-serif;">
                                        {{ $activity->kategori }}
                                    </span>
                                </div>
                            @endif

                            <!-- Unggulan Badge -->
                            @if($activity->unggulan)
                                <div class="absolute top-4 right-4">
                                    <span class="badge badge-warning badge-sm" style="font-family: 'Inter', sans-serif;">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        Unggulan
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Title -->
                            <h2 class="text-xl font-bold text-base-content mb-3 line-clamp-2 group-hover:text-primary transition-colors"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                {{ $activity->nama_kegiatan }}
                            </h2>

                            <!-- Description -->
                            @if($activity->deskripsi)
                                <p class="text-base-content/70 text-sm mb-4 line-clamp-3" style="font-family: 'Inter', sans-serif;">
                                    {{ $this->getExcerpt($activity->deskripsi, 120) }}
                                </p>
                            @endif

                            <!-- Meta Info -->
                            <div class="space-y-2 mb-4">
                                @if ($activity->tanggal_mulai)
                                    <div class="flex items-center text-xs text-base-content/60">
                                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span style="font-family: 'Inter', sans-serif;">
                                            {{ $this->formatDate($activity->tanggal_mulai) }}
                                            @if($activity->tanggal_selesai && $activity->tanggal_selesai != $activity->tanggal_mulai)
                                                - {{ $this->formatDate($activity->tanggal_selesai) }}
                                            @endif
                                        </span>
                                    </div>
                                @endif

                                @if($activity->lokasi)
                                    <div class="flex items-center text-xs text-base-content/60">
                                        <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span style="font-family: 'Inter', sans-serif;">{{ Str::limit($activity->lokasi, 30) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('activity.detail', $activity->id) }}"
                                    class="btn btn-primary btn-sm" style="font-family: 'Inter', sans-serif;"
                                    wire:navigate>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $activities->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <svg class="w-24 h-24 mx-auto text-base-content/20 mb-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-2xl font-bold text-base-content mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                        @if($search || $selectedCategory !== 'semua')
                            Tidak Ada Kegiatan Ditemukan
                        @else
                            Belum Ada Kegiatan
                        @endif
                    </h3>
                    <p class="text-base-content/60 mb-6" style="font-family: 'Inter', sans-serif;">
                        @if($search || $selectedCategory !== 'semua')
                            Coba ubah kata kunci pencarian atau filter kategori.
                        @else
                            Kegiatan sekolah akan ditampilkan di sini ketika sudah tersedia.
                        @endif
                    </p>
                    @if($search || $selectedCategory !== 'semua')
                        <button wire:click="$set('search', '')" wire:click="$set('selectedCategory', 'semua')"
                            class="btn btn-primary" style="font-family: 'Inter', sans-serif;">
                            Reset Filter
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>