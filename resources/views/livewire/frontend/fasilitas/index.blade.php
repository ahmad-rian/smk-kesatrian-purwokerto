<div class="min-h-screen bg-base-100">
    <!-- Hero Section dengan Professional Gradient -->
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 py-20 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div
                class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse">
            </div>
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000">
            </div>
            <div
                class="absolute bottom-0 left-1/2 w-96 h-96 bg-slate-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6"
                style="font-family: 'Bricolage Grotesque', sans-serif;">
                Fasilitas <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Unggulan</span>
            </h1>
            <p class="text-xl text-slate-200 max-w-3xl mx-auto leading-relaxed"
                style="font-family: 'Inter', sans-serif;">
                Nikmati fasilitas modern dan lengkap yang mendukung proses pembelajaran berkualitas tinggi
            </p>

            {{-- <!-- Professional Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-12">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400 mb-2">{{ $facilities->count() }}</div>
                    <div class="text-slate-300 text-sm">Fasilitas</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-400 mb-2">{{ count($categories) - 1 }}</div>
                    <div class="text-slate-300 text-sm">Kategori</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-slate-400 mb-2">100%</div>
                    <div class="text-slate-300 text-sm">Terawat</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-400 mb-2">24/7</div>
                    <div class="text-slate-300 text-sm">Akses</div>
                </div>
            </div> --}}
        </div>
    </section>

    <!-- Filter Section dengan MaryUI -->
    <section class="py-12 bg-base-200 border-b border-base-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-base-content mb-2"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Filter Fasilitas
                </h2>
                <p class="text-base-content/70" style="font-family: 'Inter', sans-serif;">
                    Temukan fasilitas sesuai kebutuhan Anda
                </p>
            </div>

            <!-- Filter Layout dengan Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Filter Program Studi (Kiri) -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-base-content mb-4 text-center lg:text-left"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                            <path
                                d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                            </path>
                        </svg>
                        Program Studi
                    </h3>
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        <button wire:click="filterByProgram('all')"
                            class="btn {{ $selectedProgram === 'all' ? 'btn-primary' : 'btn-outline border-base-content text-base-content hover:bg-base-content hover:text-base-100' }} transition-all duration-300 transform hover:scale-105"
                            style="font-family: 'Inter', sans-serif;">
                            Semua Program
                        </button>
                        @foreach ($studyPrograms as $program)
                            <button wire:click="filterByProgram('{{ $program->id }}')"
                                class="btn {{ $selectedProgram == $program->id ? 'btn-primary' : 'btn-outline border-base-content text-base-content hover:bg-base-content hover:text-base-100' }} transition-all duration-300 transform hover:scale-105"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $program->nama }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Filter Kategori Fasilitas (Kanan) -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-base-content mb-4 text-center lg:text-left"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Kategori Fasilitas
                    </h3>
                    <div class="flex flex-wrap gap-3 justify-center lg:justify-start">
                        @foreach ($categories as $key => $category)
                            <button wire:click="filterByCategory('{{ $key }}')"
                                class="btn {{ $selectedCategory === $key ? 'btn-primary' : 'btn-outline border-base-content text-base-content hover:bg-base-content hover:text-base-100' }} transition-all duration-300 transform hover:scale-105"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $category }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Grid dengan Layout yang Diperbaiki -->
    <section class="py-16 bg-base-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($facilities->count() > 0)
                <!-- Results Counter -->
                <div class="mb-8 text-center">
                    <p class="text-base-content/70" style="font-family: 'Inter', sans-serif;">
                        Menampilkan <span class="font-semibold text-base-content">{{ $facilities->count() }}</span>
                        fasilitas
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach ($facilities as $facility)
                        <div class="group">
                            <div
                                class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-base-300 h-full">
                                <!-- Facility Image dengan Multiple Images Support -->
                                <figure class="relative overflow-hidden">
                                    @php
                                        $primaryImage = $facility->getPrimaryImageUrl();
                                        $imageCount = $facility->images->count();
                                    @endphp

                                    @if ($primaryImage)
                                        <img src="{{ $primaryImage }}" alt="{{ $facility->nama }}"
                                            class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-700">

                                        <!-- Image Counter Badge -->
                                        @if ($imageCount > 1)
                                            <div class="absolute top-3 right-3">
                                                <div class="badge badge-neutral badge-sm font-semibold flex items-center gap-1"
                                                    style="font-family: 'Inter', sans-serif;">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $imageCount }}
                                                </div>
                                            </div>
                                        @endif
                                    @elseif ($facility->gambar)
                                        <!-- Fallback ke gambar lama -->
                                        <img src="{{ Storage::url($facility->gambar) }}" alt="{{ $facility->nama }}"
                                            class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-700">
                                    @else
                                        <div
                                            class="w-full h-48 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
                                            <svg class="w-16 h-16 text-base-100" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif

                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3">
                                        <div class="badge badge-primary badge-lg font-semibold"
                                            style="font-family: 'Inter', sans-serif;">
                                            {{ $facility->kategori }}
                                        </div>
                                    </div>

                                    <!-- Always Visible Action Button -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent">
                                        <div class="absolute bottom-3 left-3 right-3 space-y-2">
                                            <!-- Detail Page Button -->
                                            <a href="{{ route('fasilitas.detail', ['slug' => $facility->id]) }}"
                                                class="btn btn-outline btn-sm w-full text-white border-white hover:bg-white hover:text-primary"
                                                style="font-family: 'Inter', sans-serif;">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                    </path>
                                                </svg>
                                                Detail Lengkap
                                            </a>
                                        </div>
                                    </div>
                                </figure>

                                <!-- Facility Info -->
                                <div class="card-body p-5 flex-1">
                                    <h3 class="card-title text-lg font-bold text-base-content mb-2 group-hover:text-primary transition-colors duration-300 line-clamp-2"
                                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                                        {{ $facility->nama }}
                                    </h3>

                                    @if ($facility->deskripsi)
                                        <p class="text-base-content/70 mb-3 text-sm line-clamp-3"
                                            style="font-family: 'Inter', sans-serif;">
                                            {{ Str::limit($facility->deskripsi, 100) }}
                                        </p>
                                    @endif

                                    @if ($facility->studyProgram)
                                        <div class="mt-auto">
                                            <div class="badge badge-outline badge-sm"
                                                style="font-family: 'Inter', sans-serif;">
                                                <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                                        clip-rule="evenodd"></path>
                                                    <path
                                                        d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                                                    </path>
                                                </svg>
                                                <span class="truncate">{{ $facility->studyProgram->nama }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="card bg-base-100 shadow-xl max-w-md mx-auto">
                        <div class="card-body items-center text-center">
                            <div class="w-24 h-24 bg-base-200 rounded-full flex items-center justify-center mb-6">
                                <svg class="w-12 h-12 text-base-content/40" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="card-title text-base-content mb-2"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                Tidak Ada Fasilitas
                            </h3>
                            <p class="text-base-content/70" style="font-family: 'Inter', sans-serif;">
                                Tidak ditemukan fasilitas dengan filter yang dipilih.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-base-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="max-w-3xl mx-auto">
                <h2 class="text-4xl font-bold text-base-content mb-6"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Tertarik dengan Fasilitas Kami?
                </h2>
                <p class="text-xl text-base-content/70 mb-8" style="font-family: 'Inter', sans-serif;">
                    Bergabunglah dengan SMK Kesatrian dan nikmati fasilitas terbaik untuk mendukung pendidikan dan
                    pengembangan karir Anda.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('kontak') }}"
                        class="btn btn-primary btn-lg px-8 transform hover:scale-105 transition-all duration-300"
                        style="font-family: 'Inter', sans-serif;">
                        Hubungi Kami
                    </a>
                    <a href="{{ route('jurusan') }}"
                        class="btn btn-outline btn-lg px-8 transform hover:scale-105 transition-all duration-300"
                        style="font-family: 'Inter', sans-serif;">
                        Lihat Program Studi
                    </a>
                </div>
            </div>
        </div>
    </section>



    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Custom scrollbar untuk modal */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Masonry layout enhancement */
        @supports (columns: 1) {
            .columns-1>* {
                break-inside: avoid;
                page-break-inside: avoid;
            }
        }
    </style>
</div>
