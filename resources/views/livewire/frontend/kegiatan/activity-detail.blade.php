<div class="min-h-screen bg-base-100">
    @if ($activity)
        <!-- Modern Hero Section -->
        <section class="bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 text-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0"
                    style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 50px 50px;">
                </div>
            </div>

            <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 relative z-10">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-white/90 pt-6 pb-8"
                    style="font-family: 'Inter', sans-serif;">
                    <a href="{{ route('home') }}"
                        class="hover:text-white transition-colors duration-200 flex items-center" wire:navigate>
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        Beranda
                    </a>
                    <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                    <a href="{{ route('kegiatan') }}" class="hover:text-white transition-colors duration-200"
                        wire:navigate>
                        Kegiatan
                    </a>
                    <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                        </path>
                    </svg>
                    <span class="text-white/70 max-w-xs truncate">
                        {{ Str::limit($activity->nama_kegiatan, 30) }}
                    </span>
                </nav>

                <!-- Header Content -->
                <div class="pb-16">
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        @if ($activity->kategori)
                            <span
                                class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full border border-white/30"
                                style="font-family: 'Inter', sans-serif;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                    </path>
                                </svg>
                                {{ $activity->kategori }}
                            </span>
                        @endif
                        <span class="text-white/80 text-sm font-medium" style="font-family: 'Inter', sans-serif;">
                            {{ $activity->created_at->format('d F Y') }}
                        </span>
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $activity->nama_kegiatan }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-6 text-white/90">
                        @if ($activity->tanggal_kegiatan)
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <span class="font-medium" style="font-family: 'Inter', sans-serif;">
                                    {{ \Carbon\Carbon::parse($activity->tanggal_kegiatan)->format('d F Y') }}
                                </span>
                            </div>
                        @endif

                        @if ($activity->lokasi)
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="font-medium"
                                    style="font-family: 'Inter', sans-serif;">{{ $activity->lokasi }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Content Section -->
        <section class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Featured Image Section -->
                    <div class="bg-base-100 rounded-3xl shadow-lg overflow-hidden border border-base-300">
                        @if ($activity->gambar)
                            <div class="relative group">
                                <!-- Container with aspect ratio that adapts to image -->
                                <div class="relative w-full">
                                    <img src="{{ Storage::url($activity->gambar) }}"
                                        alt="{{ $activity->nama_kegiatan }}"
                                        class="w-full h-auto max-h-[600px] object-contain bg-gradient-to-br from-base-200 to-primary/20"
                                        onload="this.parentElement.style.minHeight = 'auto'"
                                        onerror="this.parentElement.innerHTML='<div class=&quot;w-full h-96 bg-gradient-to-br from-base-200 via-base-300 to-primary/20 flex items-center justify-center&quot;><div class=&quot;text-center p-8&quot;><div class=&quot;p-6 bg-base-100/80 backdrop-blur-sm rounded-2xl shadow-lg&quot;><svg class=&quot;w-16 h-16 text-primary mx-auto mb-4&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;2&quot; d=&quot;M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z&quot;></path></svg><h3 class=&quot;text-lg font-semibold text-base-content mb-2&quot;>Gambar Tidak Dapat Dimuat</h3><p class=&quot;text-sm text-base-content/70&quot;>Terjadi kesalahan saat memuat gambar</p></div></div></div>'"
                                        style="min-height: 300px;">

                                    <!-- Overlay untuk zoom effect -->
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 cursor-pointer"
                                        onclick="openImageModal(this)">
                                        <div
                                            class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <div class="p-3 bg-black/50 backdrop-blur-sm rounded-full text-white">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Placeholder Image -->
                            <div
                                class="w-full h-96 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                <div class="text-center p-8">
                                    <div class="p-6 bg-base-100/80 backdrop-blur-sm rounded-2xl shadow-lg">
                                        <svg class="w-16 h-16 text-primary mx-auto mb-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <h3 class="text-lg font-semibold text-base-content mb-2"
                                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                                            Dokumentasi Kegiatan
                                        </h3>
                                        <p class="text-sm text-base-content/70"
                                            style="font-family: 'Inter', sans-serif;">
                                            Gambar kegiatan akan ditampilkan di sini
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Image Caption -->
                        <div class="p-6 bg-base-200">
                            <p class="text-base-content/70 text-sm font-medium flex items-center"
                                style="font-family: 'Inter', sans-serif;">
                                <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Dokumentasi Kegiatan: {{ $activity->nama_kegiatan }}
                            </p>
                        </div>
                    </div>

                    <!-- Detail Kegiatan -->
                    @if ($activity->tanggal_kegiatan || $activity->lokasi)
                        <div class="bg-base-100 rounded-2xl shadow-lg p-6 border border-base-300">
                            <h2 class="text-xl font-bold text-base-content mb-4 flex items-center"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                <svg class="w-5 h-5 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Detail Kegiatan
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if ($activity->tanggal_kegiatan)
                                    <div class="flex items-center gap-3 text-base-content">
                                        <div class="p-2 bg-primary/20 rounded-lg">
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-base-content">Tanggal Kegiatan:</span>
                                            <div class="text-sm text-base-content/80"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ \Carbon\Carbon::parse($activity->tanggal_kegiatan)->format('d F Y') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($activity->lokasi)
                                    <div class="flex items-center gap-3 text-base-content">
                                        <div class="p-2 bg-primary/20 rounded-lg">
                                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-base-content">Lokasi:</span>
                                            <div class="text-sm text-base-content/80"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ $activity->lokasi }}</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Deskripsi Kegiatan -->
                    @if ($activity->deskripsi)
                        <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                            <div class="p-8">
                                <h2 class="text-2xl font-bold text-base-content mb-6 flex items-center"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    <div class="p-2 bg-primary/20 rounded-xl mr-3">
                                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    Tentang Kegiatan
                                </h2>
                                <div class="prose prose-lg max-w-none text-base-content leading-relaxed"
                                    style="font-family: 'Inter', sans-serif;">
                                    {!! nl2br(e($activity->deskripsi)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Keterangan Tambahan -->
                    @if ($activity->keterangan)
                        <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                            <div class="p-8">
                                <h2 class="text-2xl font-bold text-base-content mb-6 flex items-center"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    <div class="p-2 bg-secondary/20 rounded-xl mr-3">
                                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    Informasi Tambahan
                                </h2>
                                <div class="prose prose-lg max-w-none text-base-content leading-relaxed"
                                    style="font-family: 'Inter', sans-serif;">
                                    {!! nl2br(e($activity->keterangan)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Info Kegiatan -->
                    <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-base-content mb-6 flex items-center"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                <div class="p-2 bg-primary/20 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                Informasi Kegiatan
                            </h3>
                            <div class="space-y-6">
                                @if ($activity->tanggal_kegiatan)
                                    <div class="flex items-start gap-4">
                                        <div class="p-3 bg-primary/20 rounded-xl">
                                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-base-content/70 mb-1">Tanggal Kegiatan
                                            </p>
                                            <p class="text-lg font-semibold text-base-content"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ \Carbon\Carbon::parse($activity->tanggal_kegiatan)->format('d F Y') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if ($activity->lokasi)
                                    <div class="flex items-start gap-4">
                                        <div class="p-3 bg-success/20 rounded-xl">
                                            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-base-content/70 mb-1">Lokasi</p>
                                            <p class="text-lg font-semibold text-base-content"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ $activity->lokasi }}
                                            </p>
                                        </div>
                                    </div>
                                @endif

                                @if ($activity->kategori)
                                    <div class="flex items-start gap-4">
                                        <div class="p-3 bg-secondary/20 rounded-xl">
                                            <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-base-content/70 mb-1">Kategori</p>
                                            <p class="text-lg font-semibold text-base-content"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ $activity->kategori }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Kegiatan Terkait -->
                    @if ($relatedActivities->count() > 0)
                        <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-base-content mb-6 flex items-center"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    <div class="p-2 bg-accent/20 rounded-xl mr-3">
                                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                    Kegiatan Terkait
                                </h3>
                                <div class="space-y-4">
                                    @foreach ($relatedActivities as $related)
                                        <div class="border border-base-300 rounded-2xl p-4 hover:shadow-lg hover:border-primary transition-all duration-300 group cursor-pointer"
                                            onclick="window.location.href='{{ route('activity.detail', $related->id) }}'">
                                            <div class="flex gap-4">
                                                @if ($related->gambar)
                                                    <img src="{{ Storage::url($related->gambar) }}"
                                                        alt="{{ $related->nama_kegiatan }}"
                                                        class="w-20 h-20 object-cover rounded-xl flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                    <div
                                                        class="w-20 h-20 bg-base-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-8 h-8 text-base-content/40" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-bold text-base line-clamp-2 mb-2 text-base-content group-hover:text-primary transition-colors"
                                                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                                                        {{ $related->nama_kegiatan }}
                                                    </h4>
                                                    <div class="flex items-center gap-2 text-sm text-base-content/70">
                                                        <div class="p-1 bg-primary/20 rounded-lg">
                                                            <svg class="w-4 h-4 text-primary" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                        <span class="font-medium"
                                                            style="font-family: 'Inter', sans-serif;">
                                                            {{ $related->created_at->format('d M Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Tombol Kembali -->
                    <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                        <div class="p-6">
                            <a href="{{ route('kegiatan') }}"
                                class="btn btn-primary w-full rounded-2xl transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl"
                                style="font-family: 'Inter', sans-serif;" wire:navigate>
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Kegiatan
                            </a>
                        </div>
                    </div>

                    <!-- Share Section -->
                    <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-base-content mb-6 flex items-center"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                <div class="p-2 bg-info/20 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                        </path>
                                    </svg>
                                </div>
                                Bagikan Kegiatan
                            </h3>
                            <div class="flex gap-4">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                    target="_blank"
                                    class="btn btn-circle bg-blue-600 border-blue-600 text-white hover:bg-blue-700 hover:border-blue-700 hover:scale-110 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>

                                <a href="https://wa.me/?text={{ urlencode($activity->nama_kegiatan . ' - ' . request()->url()) }}"
                                    target="_blank"
                                    class="btn btn-circle bg-green-600 border-green-600 text-white hover:bg-green-700 hover:border-green-700 hover:scale-110 transition-all duration-300 shadow-lg hover:shadow-xl">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Image Modal -->
        <div id="imageModal"
            class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
            <div class="relative max-w-7xl max-h-full">
                <button onclick="closeImageModal()"
                    class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <img id="modalImage" src="" alt=""
                    class="max-w-full max-h-full object-contain rounded-lg shadow-2xl">
            </div>
        </div>

        <script>
            function openImageModal(element) {
                const img = element.parentElement.querySelector('img');
                const modal = document.getElementById('imageModal');
                const modalImg = document.getElementById('modalImage');

                if (img && modal && modalImg) {
                    modalImg.src = img.src;
                    modalImg.alt = img.alt;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    document.body.style.overflow = 'hidden';
                }
            }

            function closeImageModal() {
                const modal = document.getElementById('imageModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    document.body.style.overflow = 'auto';
                }
            }

            // Close modal when clicking outside the image
            document.getElementById('imageModal')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeImageModal();
                }
            });

            // Close modal with escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImageModal();
                }
            });
        </script>
    @else
        <!-- Error State -->
        <div class="min-h-screen flex items-center justify-center bg-base-100">
            <div class="text-center max-w-md mx-auto px-6">
                <div class="mb-8">
                    <div class="w-32 h-32 mx-auto bg-base-200 rounded-3xl shadow-lg flex items-center justify-center">
                        <svg class="w-16 h-16 text-base-content/40" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-base-content mb-4"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Kegiatan Tidak Ditemukan
                </h1>
                <p class="text-lg text-base-content/70 mb-8 leading-relaxed"
                    style="font-family: 'Inter', sans-serif;">
                    Maaf, kegiatan yang Anda cari tidak dapat ditemukan atau mungkin telah dihapus.
                </p>
                <a href="{{ route('kegiatan') }}"
                    class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl"
                    style="font-family: 'Inter', sans-serif;" wire:navigate>
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Kegiatan
                </a>
            </div>
        </div>
    @endif
</div>
