<div class="min-h-screen bg-base-100 transition-colors duration-300">
    <!-- Hero Section dengan Logo dan Nama Sekolah -->
    <section
        class="relative bg-gradient-to-r from-base-200 via-base-200 to-base-300 py-16 transition-colors duration-300">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-green-500/5 to-green-600/5"></div>
        <div class="relative container mx-auto px-4 text-center">
            <div class="mb-8">
                <div class="relative inline-block">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-green-600/20 rounded-full blur-xl opacity-50">
                    </div>
                    @if ($schoolLogo && $schoolLogo !== '/images/placeholder-image.svg')
                        <div
                            class="relative w-28 h-28 sm:w-32 sm:h-32 mx-auto mb-6 rounded-full shadow-xl overflow-hidden">
                            <img src="{{ $schoolLogo }}" alt="Logo {{ $schoolName }}"
                                class="w-full h-full object-cover transition-all duration-300">
                        </div>
                    @else
                        <div
                            class="relative w-28 h-28 sm:w-32 sm:h-32 mx-auto mb-6 rounded-full shadow-xl bg-gradient-to-br from-green-500/20 to-green-600/20 border-2 border-green-500/30 flex items-center justify-center">
                            <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222">
                                </path>
                            </svg>
                        </div>
                    @endif
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 text-base-content"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    {{ $schoolName }}
                </h1>
                @if ($schoolDescription)
                    <p class="text-lg md:text-xl text-base-content/70 max-w-3xl mx-auto transition-colors duration-300"
                        style="font-family: 'Inter', sans-serif;">
                        {{ $schoolDescription }}
                    </p>
                @endif
            </div>
        </div>
    </section>

    <!-- Visi dan Misi Section -->
    <section class="py-16 bg-base-200/50 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-base-content mb-4 transition-colors duration-300"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Visi & Misi
                </h2>
                <div class="w-20 h-1 bg-gradient-to-r from-green-500 to-green-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-6xl mx-auto">
                <!-- Visi -->
                <div
                    class="bg-base-100 p-6 sm:p-8 rounded-2xl shadow-lg border border-base-300 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-green-600 rounded-full flex items-center justify-center mr-4 shadow-md">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-base-content transition-colors duration-300"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Visi
                        </h3>
                    </div>
                    <div class="text-base-content/80 leading-relaxed transition-colors duration-300"
                        style="font-family: 'Inter', sans-serif;">
                        @if ($schoolVision)
                            {!! nl2br(e($schoolVision)) !!}
                        @else
                            <p>Menjadi SMK unggulan yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing
                                global pada tahun 2030.</p>
                        @endif
                    </div>
                </div>

                <!-- Misi -->
                <div
                    class="bg-base-100 p-6 sm:p-8 rounded-2xl shadow-lg border border-base-300 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 sm:w-12 sm:h-12 bg-green-500 rounded-full flex items-center justify-center mr-4 shadow-md">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl sm:text-2xl font-bold text-base-content transition-colors duration-300"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Misi
                        </h3>
                    </div>
                    <div class="text-base-content/80 leading-relaxed transition-colors duration-300"
                        style="font-family: 'Inter', sans-serif;">
                        @if ($schoolMission)
                            @php
                                $misiLines = explode("\n", $schoolMission);
                                $counter = 1;
                            @endphp
                            @foreach ($misiLines as $line)
                                @if (trim($line))
                                    <div class="flex mb-3">
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 bg-green-500/20 text-green-600 text-sm font-semibold rounded-full mr-3 mt-0.5 flex-shrink-0">
                                            {{ $counter++ }}
                                        </span>
                                        <p class="flex-1">{{ trim($line) }}</p>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="space-y-3">
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-green-500/20 text-green-600 text-sm font-semibold rounded-full mr-3 mt-0.5 flex-shrink-0">1</span>
                                    <p class="flex-1">Menyelenggarakan pendidikan kejuruan yang berkualitas dan relevan
                                        dengan kebutuhan industri</p>
                                </div>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-green-500/20 text-green-600 text-sm font-semibold rounded-full mr-3 mt-0.5 flex-shrink-0">2</span>
                                    <p class="flex-1">Mengembangkan karakter siswa yang berakhlak mulia dan berjiwa
                                        entrepreneur</p>
                                </div>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-green-500/20 text-green-600 text-sm font-semibold rounded-full mr-3 mt-0.5 flex-shrink-0">3</span>
                                    <p class="flex-1">Meningkatkan kompetensi tenaga pendidik dan kependidikan secara
                                        berkelanjutan</p>
                                </div>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-green-500/20 text-green-600 text-sm font-semibold rounded-full mr-3 mt-0.5 flex-shrink-0">4</span>
                                    <p class="flex-1">Menyediakan sarana dan prasarana pembelajaran yang modern dan
                                        memadai</p>
                                </div>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-green-500/20 text-green-600 text-sm font-semibold rounded-full mr-3 mt-0.5 flex-shrink-0">5</span>
                                    <p class="flex-1">Menjalin kerjasama dengan dunia usaha dan dunia industri (DUDI)
                                    </p>
                                </div>
                                <div class="flex">
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 bg-green-500/20 text-green-600 text-sm font-semibold rounded-full mr-3 mt-0.5 flex-shrink-0">6</span>
                                    <p class="flex-1">Mengembangkan budaya mutu dan inovasi dalam setiap kegiatan
                                        sekolah</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Lokasi Section -->
    <section class="py-16 bg-base-100 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-base-content mb-4 transition-colors duration-300"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Lokasi Sekolah
                </h2>
                <div class="w-20 h-1 bg-gradient-to-r from-green-500 to-green-600 mx-auto rounded-full"></div>
            </div>

            <div class="max-w-4xl mx-auto">
                <!-- Google Maps -->
                <div
                    class="bg-base-100 p-6 sm:p-8 rounded-2xl shadow-lg border border-base-300 transition-all duration-300 hover:shadow-xl">
                    <div class="mb-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-base-content mb-2 transition-colors duration-300"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            {{ $schoolName }}
                        </h3>
                        <div class="flex items-start text-base-content/70">
                            <svg class="w-5 h-5 mr-2 mt-0.5 text-green-600 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span style="font-family: 'Inter', sans-serif;">
                                {{ $schoolAddress }}
                            </span>
                        </div>
                    </div>

                    <div class="relative">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15833.567890123456!2d109.2366495!3d-7.4189099!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655ee7ae9a4f5f%3A0x9eaa60babff78f95!2sSMK%20Kesatrian%20Purwokerto!5e0!3m2!1sen!2sid!4v1234567890123&output=embed"
                            width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            class="rounded-lg shadow-md border border-base-300">
                        </iframe>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <a href="https://www.google.com/maps/dir//SMK+Kesatrian+Purwokerto,+Jalan+Ksatrian,+Karangjengkol,+Sokanegara,+Banyumas+Regency,+Central+Java/@-7.418908,109.19545,13z"
                                target="_blank"
                                class="btn bg-green-600 hover:bg-green-700 text-white border-green-600"
                                style="font-family: 'Inter', sans-serif;">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                    </path>
                                </svg>
                                Buka di Google Maps
                            </a>

                            @if ($schoolPhone || $schoolEmail)
                                <div class="flex flex-col sm:flex-row gap-3 sm:ml-auto">
                                    @if ($schoolPhone)
                                        <a href="tel:{{ $schoolPhone }}"
                                            class="btn btn-outline border-green-600 text-green-600 hover:bg-green-600 hover:text-white"
                                            style="font-family: 'Inter', sans-serif;">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                </path>
                                            </svg>
                                            {{ $schoolPhone }}
                                        </a>
                                    @endif

                                    @if ($schoolEmail)
                                        <a href="mailto:{{ $schoolEmail }}"
                                            class="btn btn-outline border-green-500 text-green-500 hover:bg-green-500 hover:text-white"
                                            style="font-family: 'Inter', sans-serif;">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ $schoolEmail }}
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
