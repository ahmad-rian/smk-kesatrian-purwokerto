<div class="min-h-screen bg-base-100">
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
                <a href="{{ route('home') }}" class="hover:text-white transition-colors duration-200 flex items-center"
                    wire:navigate>
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
                <a href="{{ route('berita') }}" class="hover:text-white transition-colors duration-200" wire:navigate>
                    Berita
                </a>
                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                    </path>
                </svg>
                <span class="text-white/70 max-w-xs truncate">
                    {{ Str::limit($news->judul, 30) }}
                </span>
            </nav>

            <!-- Header Content -->
            <div class="pb-16">
                <div class="flex flex-wrap items-center gap-3 mb-6">
                    <span
                        class="inline-flex items-center px-4 py-2 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full border border-white/30"
                        style="font-family: 'Inter', sans-serif;">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        {{ ucfirst($news->status) }}
                    </span>
                    <span class="text-white/80 text-sm font-medium" style="font-family: 'Inter', sans-serif;">
                        {{ $this->getFormattedDate($news->created_at) }}
                    </span>
                </div>

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    {{ $news->judul }}
                </h1>

                <div class="flex flex-wrap items-center gap-6 text-white/90">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium" style="font-family: 'Inter', sans-serif;">
                            {{ $this->getFormattedDate($news->created_at) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                </path>
                            </svg>
                        </div>
                        <span class="font-medium" style="font-family: 'Inter', sans-serif;">Berita Terbaru</span>
                    </div>
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
                @if ($news->gambar)
                    <div class="bg-base-100 rounded-3xl shadow-lg overflow-hidden border border-base-300">
                        <div class="relative group">
                            <!-- Container with aspect ratio that adapts to image -->
                            <div class="relative w-full">
                                <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}"
                                    class="w-full h-auto max-h-[600px] object-contain bg-gradient-to-br from-base-200 to-primary/20"
                                    onload="this.parentElement.style.minHeight = 'auto'"
                                    onerror="this.parentElement.innerHTML='<div class=&quot;w-full h-96 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center&quot;><div class=&quot;text-center p-8&quot;><div class=&quot;p-6 bg-base-100/80 backdrop-blur-sm rounded-2xl shadow-lg&quot;><svg class=&quot;w-16 h-16 text-primary mx-auto mb-4&quot; fill=&quot;none&quot; stroke=&quot;currentColor&quot; viewBox=&quot;0 0 24 24&quot;><path stroke-linecap=&quot;round&quot; stroke-linejoin=&quot;round&quot; stroke-width=&quot;2&quot; d=&quot;M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z&quot;></path></svg><h3 class=&quot;text-lg font-semibold text-base-content mb-2&quot;>Gambar Tidak Dapat Dimuat</h3><p class=&quot;text-sm text-base-content/70&quot;>Terjadi kesalahan saat memuat gambar</p></div></div></div>'"
                                    style="min-height: 300px;">

                                <!-- Overlay untuk zoom effect -->
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors duration-300 cursor-pointer"
                                    onclick="openImageModal(this)">
                                    <div
                                        class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="p-3 bg-black/50 backdrop-blur-sm rounded-full text-white">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Caption -->
                        <div class="p-6 bg-base-200">
                            <p class="text-base-content/70 text-sm font-medium flex items-center"
                                style="font-family: 'Inter', sans-serif;">
                                <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                                Dokumentasi Berita: {{ $news->judul }}
                            </p>
                        </div>
                    </div>
                @else
                    <!-- Placeholder Image -->
                    <div class="bg-base-100 rounded-3xl shadow-lg overflow-hidden border border-base-300">
                        <div
                            class="w-full h-96 bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                            <div class="text-center p-8">
                                <div class="p-6 bg-base-100/80 backdrop-blur-sm rounded-2xl shadow-lg">
                                    <svg class="w-16 h-16 text-primary mx-auto mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-base-content mb-2"
                                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                                        Dokumentasi Berita
                                    </h3>
                                    <p class="text-sm text-base-content/70" style="font-family: 'Inter', sans-serif;">
                                        Gambar berita akan ditampilkan di sini
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Image Caption -->
                        <div class="p-6 bg-base-200">
                            <p class="text-base-content/70 text-sm font-medium flex items-center"
                                style="font-family: 'Inter', sans-serif;">
                                <svg class="w-4 h-4 mr-2 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                                Dokumentasi Berita: {{ $news->judul }}
                            </p>
                        </div>
                    </div>
                @endif

                <!-- Article Content -->
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
                            Konten Berita
                        </h2>

                        <!-- Content -->
                        <div class="prose prose-lg max-w-none text-base-content leading-relaxed"
                            style="font-family: 'Inter', sans-serif;">
                            @php
                                // Bersihkan konten dari tag P yang tidak diperlukan
                                $cleanContent = $news->konten;
                                // Hapus tag P pembuka dan penutup
                                $cleanContent = preg_replace('/<\/?p[^>]*>/', '', $cleanContent);
                                // Bersihkan whitespace berlebih
                                $cleanContent = trim($cleanContent);
                                // Split berdasarkan line breaks dan buat paragraf yang proper
                                $paragraphs = array_filter(explode("\n", $cleanContent));
                            @endphp

                            @foreach ($paragraphs as $paragraph)
                                @if (trim($paragraph))
                                    <div class="mb-4 text-justify leading-7">
                                        {{ trim($paragraph) }}
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Share Section -->
                        <div class="mt-10 pt-8 border-t border-base-300">
                            <h3 class="text-xl font-bold mb-6 text-base-content flex items-center"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                <div class="p-2 bg-secondary/20 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                        </path>
                                    </svg>
                                </div>
                                Bagikan Berita Ini
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                <!-- Facebook -->
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-[#1877F2] hover:bg-[#166FE5] text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                    Facebook
                                </a>

                                <!-- Twitter -->
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($news->judul) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-[#1DA1F2] hover:bg-[#1A91DA] text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                    </svg>
                                    Twitter
                                </a>

                                <!-- WhatsApp -->
                                <a href="https://wa.me/?text={{ urlencode($news->judul . ' - ' . request()->url()) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-[#25D366] hover:bg-[#22C55E] text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                    </svg>
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Info Berita -->
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
                            Informasi Berita
                        </h3>
                        <div class="space-y-6">
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
                                    <p class="text-sm font-medium text-base-content/60 mb-1">Tanggal Publikasi</p>
                                    <p class="text-lg font-semibold text-base-content"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ $this->getFormattedDate($news->created_at) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-success/20 rounded-xl">
                                    <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-base-content/60 mb-1">Status</p>
                                    <p class="text-lg font-semibold text-base-content"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ ucfirst($news->status) }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-info/20 rounded-xl">
                                    <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-base-content/60 mb-1">Total Kunjungan</p>
                                    <p class="text-lg font-semibold text-base-content"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ number_format($news->getVisitorCount()) }} pembaca
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-secondary/20 rounded-xl">
                                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.994 1.994 0 01-1.414.586H7a2 2 0 01-2-2V5a2 2 0 012-2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-base-content/60 mb-1">Kategori</p>
                                    <p class="text-lg font-semibold text-base-content"
                                        style="font-family: 'Inter', sans-serif;">
                                        @if ($news->category)
                                            {{ $news->category->name }}
                                        @else
                                            Berita Sekolah
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Berita Terkait -->
                @if ($relatedNews->count() > 0)
                    <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-base-content mb-6 flex items-center"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                <div class="p-2 bg-secondary/20 rounded-xl mr-3">
                                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                        </path>
                                    </svg>
                                </div>
                                Berita Terkait
                            </h3>
                            <div class="space-y-4">
                                @foreach ($relatedNews as $related)
                                    <div class="border border-base-300 rounded-2xl p-4 hover:shadow-lg hover:border-primary transition-all duration-300 group cursor-pointer"
                                        onclick="window.location.href='{{ route('berita.detail', $related->slug) }}'">
                                        <div class="flex gap-4">
                                            @if ($related->gambar)
                                                <img src="{{ Storage::url($related->gambar) }}"
                                                    alt="{{ $related->judul }}"
                                                    class="w-20 h-20 object-cover rounded-xl flex-shrink-0 group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div
                                                    class="w-20 h-20 bg-base-200 rounded-xl flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-8 h-8 text-base-content/40" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-bold text-base line-clamp-2 mb-2 text-base-content group-hover:text-primary transition-colors"
                                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                                    {{ $related->judul }}
                                                </h4>
                                                <div class="flex items-center gap-2 text-sm text-base-content/60">
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
                                                        {{ $this->getFormattedDate($related->created_at) }}
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
                        <a href="{{ route('berita') }}"
                            class="inline-flex items-center justify-center w-full px-6 py-4 bg-gradient-to-r from-primary to-secondary text-primary-content font-semibold rounded-2xl hover:from-primary/90 hover:to-secondary/90 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl"
                            style="font-family: 'Inter', sans-serif;" wire:navigate>
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Berita
                        </a>
                    </div>
                </div>

                <!-- Share Section -->
                <div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-base-content mb-6 flex items-center"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            <div class="p-2 bg-primary/20 rounded-xl mr-3">
                                <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z">
                                    </path>
                                </svg>
                            </div>
                            Bagikan Berita
                        </h3>
                        <div class="flex gap-4">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                target="_blank"
                                class="flex items-center justify-center w-14 h-14 bg-[#1877F2] text-white rounded-2xl hover:bg-[#166FE5] hover:scale-110 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>

                            <a href="https://wa.me/?text={{ urlencode($news->judul . ' - ' . request()->url()) }}"
                                target="_blank"
                                class="flex items-center justify-center w-14 h-14 bg-[#25D366] text-white rounded-2xl hover:bg-[#22C55E] hover:scale-110 transition-all duration-300 shadow-lg hover:shadow-xl">
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
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
</div>
