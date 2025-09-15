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
                            Kegiatan Sekolah
                        </h1>
                        <p class="text-xl md:text-2xl opacity-90 mb-6 leading-relaxed"
                            style="font-family: 'Inter', sans-serif;">
                            Berbagai kegiatan dan acara menarik di SMK Kesatrian Purwokerto yang mengembangkan potensi
                            siswa
                        </p>
                        <div class="flex flex-wrap items-center gap-4 text-white/80">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                    </path>
                                </svg>
                                <span class="text-sm">Aktivitas Siswa</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                                <span class="text-sm">Pengembangan Karakter</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Animation -->
                <div class="lg:col-span-2 flex items-center justify-center p-6">
                    <div class="text-center">
                        <!-- Lottie Animation -->
                        <div class="mb-4">
                            <lottie-player src="/assets/animations/activity.json" background="transparent"
                                speed="1" style="width: 300px; height: 300px;" loop autoplay renderer="svg"
                                mode="normal">
                            </lottie-player>
                            <!-- Fallback jika animasi gagal -->
                            <div class="hidden animate-pulse" id="animation-fallback">
                                <div
                                    class="w-24 h-24 bg-white/20 rounded-full mx-auto mb-4 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Animation Text -->
                        <div class="text-white">
                            <h3 class="text-lg font-bold mb-2">Aktivitas Terkini</h3>
                            <p class="text-sm opacity-80">Pantau kegiatan sekolah terbaru</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile & Tablet Layout - Single Column -->
            <div class="lg:hidden py-16 text-center">
                <h1 class="text-3xl md:text-4xl font-bold mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Kegiatan Sekolah
                </h1>
                <p class="text-lg md:text-xl opacity-90 max-w-2xl mx-auto mb-6"
                    style="font-family: 'Inter', sans-serif;">
                    Berbagai kegiatan dan acara menarik di SMK Kesatrian Purwokerto
                </p>

                <!-- Mobile Animation -->
                <div class="flex justify-center mb-6">
                    <lottie-player src="/assets/animations/activity.json" background="transparent" speed="1"
                        style="width: 200px; height: 200px;" loop autoplay renderer="svg" mode="normal">
                    </lottie-player>
                </div>
            </div>
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
                        class="input input-bordered w-full pl-12 pr-4 focus:input-primary">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-base-content/40" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Category Filter -->
                @if ($categories && count($categories) > 0)
                    <div class="flex flex-wrap gap-2 justify-center">
                        <button wire:click="$set('selectedCategory', 'semua')"
                            class="btn btn-sm transition-all duration-200 {{ $selectedCategory === 'semua' ? 'btn-primary' : 'btn-outline btn-primary hover:btn-primary' }}">
                            Semua
                        </button>
                        @foreach ($categories as $category)
                            <button wire:click="$set('selectedCategory', '{{ $category }}')"
                                class="btn btn-sm transition-all duration-200 {{ $selectedCategory === $category ? 'btn-primary' : 'btn-outline btn-primary hover:btn-primary' }}">
                                {{ ucfirst($category) }}
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
                        class="bg-base-100 rounded-xl shadow-sm border border-base-300 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                        <!-- Image -->
                        <div class="relative h-48 bg-base-200 overflow-hidden">
                            @if ($activity->gambar)
                                <img src="{{ Storage::url($activity->gambar) }}" alt="{{ $activity->nama_kegiatan }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-100/50 to-slate-100/50">
                                    <svg class="w-16 h-16 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            @if ($activity->kategori)
                                <div class="absolute top-4 left-4">
                                    <span class="badge bg-blue-600 text-white border-blue-600 badge-sm"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ $activity->kategori }}
                                    </span>
                                </div>
                            @endif

                            <!-- Unggulan Badge -->
                            @if ($activity->unggulan)
                                <div class="absolute top-4 right-4">
                                    <span class="badge badge-warning badge-sm"
                                        style="font-family: 'Inter', sans-serif;">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                        Unggulan
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <!-- Title -->
                            <h2 class="text-xl font-bold text-base-content mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                {{ $activity->nama_kegiatan }}
                            </h2>

                            <!-- Description -->
                            @if ($activity->deskripsi)
                                <p class="text-base-content/70 text-sm mb-4 line-clamp-3 leading-relaxed"
                                    style="font-family: 'Inter', sans-serif;">
                                    {{ $this->getExcerpt($activity->deskripsi, 120) }}
                                </p>
                            @endif

                            <!-- Meta Info -->
                            <div class="space-y-2 mb-4">
                                @if ($activity->tanggal_mulai)
                                    <div class="flex items-center text-xs text-base-content/60">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span style="font-family: 'Inter', sans-serif;">
                                            {{ $this->formatDate($activity->tanggal_mulai) }}
                                            @if ($activity->tanggal_selesai && $activity->tanggal_selesai != $activity->tanggal_mulai)
                                                - {{ $this->formatDate($activity->tanggal_selesai) }}
                                            @endif
                                        </span>
                                    </div>
                                @endif

                                @if ($activity->lokasi)
                                    <div class="flex items-center text-xs text-base-content/60">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span
                                            style="font-family: 'Inter', sans-serif;">{{ Str::limit($activity->lokasi, 30) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Action Button -->
                            <div class="flex justify-end">
                                <a href="{{ route('activity.detail', $activity->id) }}"
                                    class="btn btn-primary btn-sm hover:scale-105 transition-transform duration-200"
                                    style="font-family: 'Inter', sans-serif;" wire:navigate>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
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
            <!-- Enhanced Empty State -->
            <div class="text-center py-16">
                <div class="max-w-md mx-auto">
                    <div class="mb-6">
                        <div class="relative w-[200px] h-[200px] mx-auto">
                            <lottie-player src="/assets/animations/activity.json" background="transparent"
                                speed="1"
                                style="width: 100%; height: 100%; background: none !important; background-color: transparent !important;"
                                loop autoplay renderer="svg" mode="normal">
                            </lottie-player>
                        </div>
                        <!-- Fallback untuk empty state -->
                        <svg class="w-24 h-24 mx-auto text-base-content/20 mb-6 hidden" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-base-content mb-4"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        @if ($search || $selectedCategory !== 'semua')
                            Tidak Ada Kegiatan Ditemukan
                        @else
                            Belum Ada Kegiatan
                        @endif
                    </h3>
                    <p class="text-base-content/60 mb-6 leading-relaxed" style="font-family: 'Inter', sans-serif;">
                        @if ($search || $selectedCategory !== 'semua')
                            Coba ubah kata kunci pencarian atau filter kategori untuk hasil yang berbeda.
                        @else
                            Kegiatan sekolah akan ditampilkan di sini ketika sudah tersedia.
                        @endif
                    </p>
                    @if ($search || $selectedCategory !== 'semua')
                        <button wire:click="$set('search', '')" wire:click="$set('selectedCategory', 'semua')"
                            class="btn btn-primary" style="font-family: 'Inter', sans-serif;">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                            Reset Filter
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
