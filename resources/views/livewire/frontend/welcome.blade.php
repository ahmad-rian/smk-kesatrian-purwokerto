<div>
    {{-- Carousel Section - Hanya padding atas yang diperkecil --}}
    {{-- Carousel Section - Mobile Navigation Fixed --}}
    <section class="carousel-section relative overflow-hidden bg-base-100">
        @if ($carouselData && $carouselData->count() > 0)
            {{-- Carousel Container --}}
            <div class="flex justify-center pt-2 pb-6 lg:pt-3 lg:pb-8">
                <div class="w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="carousel w-full h-80 md:h-[28rem] lg:h-[32rem]" x-data="{
                        currentSlide: 0,
                        totalSlides: {{ $carouselData->count() }},
                        autoPlay: true,
                        interval: null,
                    
                        init() {
                            this.startAutoPlay();
                        },
                    
                        startAutoPlay() {
                            if (this.autoPlay && this.totalSlides > 1) {
                                this.interval = setInterval(() => {
                                    this.nextSlide();
                                }, 5000);
                            }
                        },
                    
                        stopAutoPlay() {
                            if (this.interval) {
                                clearInterval(this.interval);
                                this.interval = null;
                            }
                        },
                    
                        nextSlide() {
                            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
                        },
                    
                        prevSlide() {
                            this.currentSlide = this.currentSlide === 0 ? this.totalSlides - 1 : this.currentSlide - 1;
                        },
                    
                        goToSlide(index) {
                            this.currentSlide = index;
                        }
                    }"
                        @mouseenter="stopAutoPlay()" @mouseleave="startAutoPlay()">

                        {{-- Image Container with proper overflow handling --}}
                        <div class="relative w-full h-full rounded-xl lg:rounded-2xl overflow-hidden shadow-xl">
                            @foreach ($carouselData as $index => $carousel)
                                <div class="carousel-item absolute inset-0 w-full h-full"
                                    x-show="currentSlide === {{ $index }}"
                                    x-transition:enter="transition ease-out duration-500"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                    @if ($carousel->gambar)
                                        <img src="{{ Storage::url($carousel->gambar) }}" alt="{{ $carousel->judul }}"
                                            class="w-full h-full object-cover object-center">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center">
                                            <svg class="w-16 h-16 md:w-20 md:h-20 text-primary/40" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif

                                    {{-- Text Overlay --}}
                                    <div
                                        class="absolute inset-0 flex items-center justify-center z-20 px-4 sm:px-6 lg:px-8">
                                        <div class="text-center max-w-4xl mx-auto">
                                            {{-- Carousel Title --}}
                                            @if ($carousel->judul)
                                                <h1 class="text-2xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-white mb-2 sm:mb-4 drop-shadow-2xl"
                                                    style="font-family: 'Bricolage Grotesque', sans-serif; text-shadow: 2px 2px 8px rgba(0,0,0,0.7);">
                                                    {{ $carousel->judul }}
                                                </h1>
                                            @endif

                                            {{-- Carousel Description --}}
                                            @if ($carousel->deskripsi)
                                                <p class="text-sm sm:text-lg md:text-xl lg:text-2xl xl:text-3xl text-white/95 font-medium leading-relaxed drop-shadow-lg max-w-3xl mx-auto"
                                                    style="font-family: 'Inter', sans-serif; text-shadow: 1px 1px 4px rgba(0,0,0,0.6);">
                                                    {{ $carousel->deskripsi }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Fixed Navigation Controls for Mobile --}}
                            @if ($carouselData->count() > 1)
                                {{-- Previous/Next Buttons - Improved positioning --}}
                                <div
                                    class="absolute left-2 right-2 sm:left-4 sm:right-4 top-1/2 flex -translate-y-1/2 transform justify-between z-30 pointer-events-none">
                                    <button @click="prevSlide()"
                                        class="btn btn-circle bg-black/40 hover:bg-black/60 text-white border-none backdrop-blur-sm transition-all duration-300 hover:scale-105 
                                           w-10 h-10 min-h-10 sm:w-12 sm:h-12 sm:min-h-12 md:w-14 md:h-14 md:min-h-14 
                                           shadow-lg pointer-events-auto flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button @click="nextSlide()"
                                        class="btn btn-circle bg-black/40 hover:bg-black/60 text-white border-none backdrop-blur-sm transition-all duration-300 hover:scale-105 
                                           w-10 h-10 min-h-10 sm:w-12 sm:h-12 sm:min-h-12 md:w-14 md:h-14 md:min-h-14 
                                           shadow-lg pointer-events-auto flex-shrink-0">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 md:w-6 md:h-6" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>

                                {{-- Dots Indicator - Mobile optimized --}}
                                <div
                                    class="absolute bottom-3 sm:bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-1.5 sm:space-x-2 z-30">
                                    @foreach ($carouselData as $index => $carousel)
                                        <button @click="goToSlide({{ $index }})"
                                            class="transition-all duration-300 rounded-full"
                                            :class="currentSlide === {{ $index }} ?
                                                'w-6 h-2.5 sm:w-8 sm:h-3 bg-white shadow-lg' :
                                                'w-2.5 h-2.5 sm:w-3 sm:h-3 bg-white/60 hover:bg-white/80 hover:scale-110'">
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Fallback content unchanged --}}
            <div class="flex justify-center pt-2 pb-6 lg:pt-3 lg:pb-8">
                <div class="w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                    <div
                        class="hero h-64 md:h-80 lg:h-[28rem] xl:h-[32rem] bg-gradient-to-br from-primary/10 to-secondary/10 rounded-xl lg:rounded-2xl overflow-hidden shadow-xl">
                        <div class="hero-content text-center">
                            <div class="max-w-3xl">
                                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-4 bg-gradient-to-r from-primary to-secondary bg-clip-text text-transparent"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    {{ $siteSettings->nama_sekolah ?? 'SMK Kesatrian' }}
                                </h1>
                                @if ($siteSettings && $siteSettings->tagline)
                                    <p class="text-lg md:text-xl mb-6 text-base-content/80 max-w-2xl mx-auto"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ $siteSettings->tagline }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section {{-- Berita Terbaru Section --}} <section id="berita" class="py-16 bg-base-200">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-base-content mb-4"
                style="font-family: 'Bricolage Grotesque', sans-serif;">
                Berita Terbaru
            </h2>
            <p class="text-base-content/70 max-w-2xl mx-auto" style="font-family: 'Inter', sans-serif;">
                Ikuti perkembangan terbaru dan informasi penting dari
                {{ $siteSettings->nama_sekolah ?? 'SMK Kesatrian' }}
            </p>
        </div>

        @if ($latestNews && $latestNews->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($latestNews as $news)
                    <div class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer"
                        onclick="window.location.href='{{ route('berita.detail', $news->slug) }}'">
                        @if ($news->gambar)
                            <figure class="h-48 overflow-hidden">
                                <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}"
                                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                            </figure>
                        @else
                            <figure class="h-48 bg-base-300 flex items-center justify-center">
                                <svg class="w-16 h-16 text-base-content/20" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                                    </path>
                                </svg>
                            </figure>
                        @endif

                        <div class="card-body p-4">
                            @if ($news->kategori)
                                <div class="badge badge-primary badge-sm mb-2"
                                    style="font-family: 'Inter', sans-serif;">
                                    {{ $news->kategori }}
                                </div>
                            @endif

                            <h3 class="card-title text-base line-clamp-2 mb-2"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                {{ $news->judul }}
                            </h3>

                            @if ($news->ringkasan)
                                <p class="text-sm text-base-content/70 line-clamp-3 mb-3"
                                    style="font-family: 'Inter', sans-serif;">
                                    {{ $news->ringkasan }}
                                </p>
                            @endif

                            <div class="flex justify-between items-center text-xs text-base-content/60"
                                style="font-family: 'Inter', sans-serif;">
                                <span>{{ $news->tanggal_publikasi->format('d M Y') }}</span>
                                <a href="{{ route('berita.detail', $news->slug) }}"
                                    class="text-primary hover:underline" wire:navigate>Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('berita') }}" class="btn btn-outline btn-primary"
                    style="font-family: 'Inter', sans-serif;" wire:navigate>
                    Lihat Semua Berita
                </a>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-base-content/20 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                <p class="text-base-content/60" style="font-family: 'Inter', sans-serif;">
                    Belum ada berita yang dipublikasikan
                </p>
            </div>
        @endif
    </div>
    </section>

    {{-- Kegiatan Sekolah Section --}}
    <section class="py-20 bg-base-100">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-6"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Kegiatan Sekolah
                </h2>
                <p class="text-xl text-base-content/70 max-w-3xl mx-auto" style="font-family: 'Inter', sans-serif;">
                    Berbagai kegiatan menarik yang mengembangkan potensi dan karakter siswa.
                </p>
            </div>

            {{-- Filter Kategori --}}
            @if (count($categories) > 1)
                <div class="flex flex-wrap justify-center gap-2 mb-12">
                    @foreach ($categories as $category)
                        <button wire:click="filterByCategory('{{ $category }}')"
                            class="btn btn-sm transition-all duration-300 {{ $selectedCategory === $category ? 'btn-primary' : 'btn-outline btn-primary' }}">
                            {{ ucfirst($category === 'semua' ? 'Semua Kegiatan' : $category) }}
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Grid Kegiatan --}}
            @if ($schoolActivities && $schoolActivities->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
                    wire:loading.class="opacity-50">
                    @foreach ($schoolActivities as $activity)
                        <div class="card bg-base-200 shadow-xl hover:shadow-2xl transition-all duration-300 group">
                            <figure class="px-4 pt-4">
                                @if ($activity->gambar_utama)
                                    <img src="{{ Storage::url($activity->gambar_utama) }}"
                                        alt="{{ $activity->nama_kegiatan }}"
                                        class="rounded-xl h-48 w-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div
                                        class="rounded-xl h-48 w-full bg-gradient-to-br from-primary/20 to-secondary/20 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                        <svg class="w-16 h-16 text-primary/40" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </figure>
                            <div class="card-body p-4">
                                <h3 class="card-title text-lg"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    {{ $activity->nama_kegiatan }}
                                    @if ($activity->unggulan)
                                        <div class="badge badge-primary badge-sm">Unggulan</div>
                                    @endif
                                </h3>

                                @if ($activity->kategori)
                                    <div class="badge badge-outline badge-sm mb-2">{{ ucfirst($activity->kategori) }}
                                    </div>
                                @endif

                                @if ($activity->deskripsi)
                                    <p class="text-base-content/70 text-sm line-clamp-3"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ Str::limit($activity->deskripsi, 100) }}
                                    </p>
                                @endif

                                @if ($activity->tanggal_mulai)
                                    <div class="flex items-center text-xs text-base-content/60 mt-2">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $activity->tanggal_mulai->format('d M Y') }}
                                    </div>
                                @endif

                                <div class="card-actions justify-end mt-4">
                                    <a href="{{ route('activity.detail', $activity->id) }}"
                                        class="btn btn-primary btn-sm" wire:navigate>
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
                        </div>
                    @endforeach
                </div>

                {{-- Tombol Lihat Semua Kegiatan --}}
                <div class="text-center mt-8">
                    <a href="{{ route('kegiatan') }}" class="btn btn-outline btn-primary"
                        style="font-family: 'Inter', sans-serif;" wire:navigate>
                        Lihat Semua Kegiatan
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-base-content/30 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <p class="text-base-content/60 text-lg" style="font-family: 'Inter', sans-serif;">
                        Belum ada kegiatan untuk kategori ini.
                    </p>
                </div>
            @endif
        </div>
    </section>

    {{-- Jurusan Section --}}
    <section class="py-20 bg-base-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 xl:px-12">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-6"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Jurusan
                </h2>
                <p class="text-xl text-base-content/70 max-w-3xl mx-auto" style="font-family: 'Inter', sans-serif;">
                    Pilihan jurusan yang sesuai dengan minat dan bakat untuk masa depan yang cerah.
                </p>
            </div>

            @if ($studyPrograms && $studyPrograms->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    @foreach ($studyPrograms as $program)
                        <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 group">
                            <div class="card-body text-center">
                                {{-- Icon/Gambar Program --}}
                                @if ($program->gambar)
                                    <figure class="mb-4">
                                        <img src="{{ $program->gambar_url }}" alt="{{ $program->nama }}"
                                            class="w-16 h-16 mx-auto rounded-lg object-cover group-hover:scale-110 transition-transform duration-300">
                                    </figure>
                                @else
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
                                        style="background-color: {{ $program->warna ?? '#3b82f6' }}20">
                                        <svg class="w-8 h-8" style="color: {{ $program->warna ?? '#3b82f6' }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                    </div>
                                @endif

                                {{-- Nama Program --}}
                                <h3 class="card-title justify-center mb-4 text-lg"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    {{ $program->nama }}
                                </h3>

                                {{-- Deskripsi Singkat --}}
                                @if ($program->deskripsi)
                                    <p class="text-base-content/70 text-sm line-clamp-3"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ Str::limit($program->deskripsi, 120) }}
                                    </p>
                                @endif

                                {{-- Ketua Program --}}
                                @if ($program->ketua_program)
                                    <div class="text-xs text-base-content/60 mt-3">
                                        <span class="font-medium">Ketua Program:</span> {{ $program->ketua_program }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol Lihat Semua Jurusan --}}
                <div class="text-center mt-12">
                    <a href="{{ route('jurusan') }}"
                        class="btn btn-outline btn-primary btn-lg group hover:scale-105 transition-all duration-300"
                        style="font-family: 'Inter', sans-serif;" wire:navigate>
                        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lihat Semua Jurusan
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-24 h-24 mx-auto text-base-content/30 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                        </path>
                    </svg>
                    <p class="text-base-content/60 text-lg" style="font-family: 'Inter', sans-serif;">
                        Jurusan akan segera tersedia.
                    </p>

                    {{-- Tombol Lihat Semua Jurusan untuk kondisi kosong --}}
                    <div class="mt-6">
                        <a href="{{ route('jurusan') }}"
                            class="btn btn-primary btn-lg group hover:scale-105 transition-all duration-300"
                            style="font-family: 'Inter', sans-serif;" wire:navigate>
                            <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Lihat Semua Jurusan
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform duration-300"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

</div>
