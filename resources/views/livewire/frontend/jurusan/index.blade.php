<div>
    {{-- Hero Section dengan Professional Gradient --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 py-20 overflow-hidden">
        {{-- Background Pattern --}}
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
                Program <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Studi</span>
            </h1>
            <p class="text-xl text-slate-200 max-w-3xl mx-auto leading-relaxed"
                style="font-family: 'Inter', sans-serif;">
                Temukan passion dan bakat Anda melalui program studi unggulan kami.
                Setiap jurusan dirancang untuk mempersiapkan lulusan yang kompeten dan siap kerja.
            </p>

            {{-- Professional Stats
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-12">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400 mb-2">6</div>
                    <div class="text-slate-300 text-sm">Program Studi</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-400 mb-2">95%</div>
                    <div class="text-slate-300 text-sm">Tingkat Kelulusan</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-slate-400 mb-2">87%</div>
                    <div class="text-slate-300 text-sm">Lulusan Bekerja</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-400 mb-2">50+</div>
                    <div class="text-slate-300 text-sm">Mitra Industri</div>
                </div>
            </div> --}}
        </div>
    </section>

    {{-- {{-- Filter Section --}}
    <section class="py-12 bg-base-200 border-b border-base-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-base-content mb-2"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Filter Program Studi
                </h2>
                <p class="text-base-content/70" style="font-family: 'Inter', sans-serif;">
                    Pilih kategori program studi yang sesuai dengan minat Anda
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-4">
                <button wire:click="setFilter('all')"
                    class="px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 {{ $activeFilter === 'all' ? 'btn btn-primary' : 'btn btn-outline' }}"
                    style="font-family: 'Inter', sans-serif;">
                    Semua Program
                </button>
                <button wire:click="setFilter('Teknologi')"
                    class="px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 {{ $activeFilter === 'Teknologi' ? 'btn btn-primary' : 'btn btn-outline' }}"
                    style="font-family: 'Inter', sans-serif;">
                    Teknologi
                </button>
                <button wire:click="setFilter('Kreatif')"
                    class="px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 {{ $activeFilter === 'Kreatif' ? 'btn btn-primary' : 'btn btn-outline' }}"
                    style="font-family: 'Inter', sans-serif;">
                    Kreatif
                </button>
                <button wire:click="setFilter('Bisnis')"
                    class="px-6 py-3 rounded-full font-semibold transition-all duration-300 transform hover:scale-105 {{ $activeFilter === 'Bisnis' ? 'btn btn-primary' : 'btn btn-outline' }}"
                    style="font-family: 'Inter', sans-serif;">
                    Bisnis
                </button>
            </div>
        </div>
    </section>

    {{-- Program Cards Section --}}
    <section class="py-16 bg-base-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($this->filteredPrograms as $program)
                    <div
                        class="group relative card bg-base-100 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-base-300">
                        {{-- Card Image/Header --}}
                        @if ($program['gambar'])
                            <figure class="h-48 overflow-hidden">
                                <img src="{{ $program['gambar'] }}" alt="{{ $program['nama'] }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full shadow-lg"
                                        style="background-color: {{ $program['warna'] }};">
                                        {{ $program['kode'] }}
                                    </span>
                                </div>
                            </figure>
                        @else
                            <div
                                class="h-48 bg-gradient-to-br from-slate-100 to-slate-200 relative flex items-center justify-center">
                                <div class="text-center">
                                    <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center"
                                        style="background-color: {{ $program['warna'] }}20;">
                                        @if ($program['kategori'] === 'Teknologi')
                                            <svg class="w-8 h-8" style="color: {{ $program['warna'] }};"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @elseif($program['kategori'] === 'Kreatif')
                                            <svg class="w-8 h-8" style="color: {{ $program['warna'] }};"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            <svg class="w-8 h-8" style="color: {{ $program['warna'] }};"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-sm font-medium"
                                        style="color: {{ $program['warna'] }};">{{ $program['kategori'] }}</span>
                                </div>
                                <div class="absolute top-4 left-4">
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold text-white rounded-full shadow-lg"
                                        style="background-color: {{ $program['warna'] }};">
                                        {{ $program['kode'] }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- Card Content --}}
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-base-content mb-3 group-hover:text-primary transition-colors"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                {{ $program['nama'] }}
                            </h3>
                            <p class="text-base-content/70 text-sm leading-relaxed mb-4"
                                style="font-family: 'Inter', sans-serif;">
                                {{ Str::limit($program['deskripsi'], 120) }}
                            </p>

                            {{-- Kompetensi Preview --}}
                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-base-content mb-2"
                                    style="font-family: 'Inter', sans-serif;">Kompetensi Utama:</h4>
                                <div class="flex flex-wrap gap-1">
                                    @foreach (array_slice($program['kompetensi'], 0, 2) as $kompetensi)
                                        <span class="badge badge-outline badge-sm">
                                            {{ $kompetensi }}
                                        </span>
                                    @endforeach
                                    @if (count($program['kompetensi']) > 2)
                                        <span class="badge badge-primary badge-sm">
                                            +{{ count($program['kompetensi']) - 2 }} lainnya
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Statistik Mini --}}
                            <div class="flex items-center text-sm text-base-content/60 mb-4"
                                style="font-family: 'Inter', sans-serif;">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ count($program['kompetensi']) }} Kompetensi</span>
                                <span class="mx-2">•</span>
                                <span>{{ count($program['prospek_karir']) }} Prospek Karir</span>
                            </div>

                            {{-- Ketua Program --}}
                            @if ($program['ketua_program'])
                                <div class="flex items-center text-sm text-base-content/60 mb-4"
                                    style="font-family: 'Inter', sans-serif;">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd">
                                        </path>
                                    </svg>
                                    {{ $program['ketua_program'] }}
                                </div>
                            @endif

                            {{-- Action Button --}}
                            <button wire:click="showDetail('{{ $program['id'] }}')" class="btn btn-primary w-full"
                                style="font-family: 'Inter', sans-serif;">
                                Lihat Detail Program
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Modal Detail Program --}}
    @if ($showModal && $selectedProgram)
        <div class="modal modal-open">
            <div class="modal-box max-w-4xl w-full mx-4">
                {{-- Modal Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 bg-primary/20">
                            <span class="text-lg font-bold text-primary">
                                {{ $selectedProgram['kode'] }}
                            </span>
                        </div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-base-content"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                {{ $selectedProgram['nama'] }}
                            </h3>
                            <p class="text-sm text-base-content/60" style="font-family: 'Inter', sans-serif;">Program
                                Studi {{ $selectedProgram['kategori'] }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="btn btn-sm btn-circle btn-ghost">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Content --}}
                <div class="px-4 sm:px-6 py-6 max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
                        {{-- Deskripsi --}}
                        <div>
                            <h4 class="text-lg font-semibold text-base-content mb-3"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">Deskripsi Program</h4>
                            <p class="text-base-content/80 leading-relaxed mb-6"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $selectedProgram['deskripsi'] }}
                            </p>

                            @if ($selectedProgram['ketua_program'])
                                <div class="bg-base-200 rounded-lg p-4">
                                    <h5 class="font-semibold text-base-content mb-2"
                                        style="font-family: 'Inter', sans-serif;">Ketua Program Studi</h5>
                                    <p class="text-base-content/80" style="font-family: 'Inter', sans-serif;">
                                        {{ $selectedProgram['ketua_program'] }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Kompetensi & Prospek --}}
                        <div>
                            {{-- Kompetensi --}}
                            <div class="mb-6">
                                <h4 class="text-lg font-semibold text-base-content mb-3"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">Kompetensi yang Dipelajari
                                </h4>
                                <ul class="space-y-3">
                                    @foreach ($selectedProgram['kompetensi'] as $kompetensi)
                                        <li class="flex items-start">
                                            <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0 text-success"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-base-content/80 text-sm"
                                                style="font-family: 'Inter', sans-serif;">{{ $kompetensi }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Prospek Karir --}}
                            <div>
                                <h4 class="text-lg font-semibold text-base-content mb-3"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">Prospek Karir</h4>
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach ($selectedProgram['prospek_karir'] as $karir)
                                        <div class="flex items-center p-3 bg-base-200 rounded-lg">
                                            <svg class="w-4 h-4 mr-3 text-primary" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                                    clip-rule="evenodd"></path>
                                                <path
                                                    d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z">
                                                </path>
                                            </svg>
                                            <span class="text-base-content/80 text-sm font-medium"
                                                style="font-family: 'Inter', sans-serif;">{{ $karir }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="modal-action">
                    <button wire:click="closeModal" class="btn btn-outline"
                        style="font-family: 'Inter', sans-serif;">
                        Tutup
                    </button>
                    @if (Route::has('frontend.contact'))
                        <a href="{{ route('frontend.contact') }}" class="btn btn-primary"
                            style="font-family: 'Inter', sans-serif;">
                            Hubungi Kami
                        </a>
                    @else
                        <a href="/kontak" class="btn btn-primary" style="font-family: 'Inter', sans-serif;">
                            Hubungi Kami
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif



    <style>
        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</div>
