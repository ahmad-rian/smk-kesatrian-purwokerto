<div class="min-h-screen bg-base-100 transition-colors duration-300">
    <!-- Hero Section -->
    <section
        class="relative bg-gradient-to-r from-base-200 via-base-200 to-base-300 py-16 transition-colors duration-300">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-green-500/5 to-green-600/5"></div>
        <div class="relative container mx-auto px-4 text-center">
            <div class="mb-8">
                <div class="relative inline-block">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-green-500/20 to-green-600/20 rounded-full blur-xl opacity-50">
                    </div>
                    <div
                        class="relative w-20 h-20 sm:w-24 sm:h-24 mx-auto mb-6 rounded-full shadow-xl bg-gradient-to-br from-green-500/20 to-green-600/20 border-2 border-green-500/30 flex items-center justify-center">
                        <x-mary-icon name="o-envelope" class="w-10 h-10 text-green-600" />
                    </div>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 text-base-content transition-colors duration-300"
                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Hubungi Kami
                </h1>
                <p class="text-lg md:text-xl text-base-content/70 max-w-3xl mx-auto transition-colors duration-300"
                    style="font-family: 'Inter', sans-serif;">
                    Kami siap membantu Anda. Jangan ragu untuk menghubungi kami kapan saja.
                </p>
            </div>
            <div class="w-20 h-1 bg-gradient-to-r from-green-500 to-green-600 mx-auto rounded-full"></div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-16 bg-base-200/50 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">
                <!-- Contact Information Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
                    <!-- Address Card -->
                    <div
                        class="bg-base-100 p-6 rounded-2xl shadow-lg border border-base-300 hover:shadow-xl hover:border-green-500/30 transition-all duration-300 group">
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 mx-auto mb-4">
                                <x-mary-icon name="o-map-pin" class="w-8 h-8 text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-base-content mb-2 transition-colors duration-300"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                Alamat
                            </h3>
                            <p class="text-base-content/70 text-sm leading-relaxed transition-colors duration-300"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $schoolAddress }}
                            </p>
                        </div>
                    </div>

                    <!-- Phone Card -->
                    <div
                        class="bg-base-100 p-6 rounded-2xl shadow-lg border border-base-300 hover:shadow-xl hover:border-green-500/30 transition-all duration-300 group">
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 mx-auto mb-4">
                                <x-mary-icon name="o-phone" class="w-8 h-8 text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-base-content mb-2 transition-colors duration-300"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                Telepon
                            </h3>
                            <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $schoolPhone) }}"
                                class="text-green-600 hover:text-green-700 font-medium text-sm transition-colors duration-200"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $schoolPhone }}
                            </a>
                        </div>
                    </div>

                    <!-- Email Card -->
                    <div
                        class="bg-base-100 p-6 rounded-2xl shadow-lg border border-base-300 hover:shadow-xl hover:border-green-500/30 transition-all duration-300 group">
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 mx-auto mb-4">
                                <x-mary-icon name="o-envelope" class="w-8 h-8 text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-base-content mb-2 transition-colors duration-300"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                Email
                            </h3>
                            <a href="mailto:{{ $schoolEmail }}"
                                class="text-green-600 hover:text-green-700 font-medium text-sm transition-colors duration-200 break-all"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $schoolEmail }}
                            </a>
                        </div>
                    </div>

                    <!-- Website Card -->
                    <div
                        class="bg-base-100 p-6 rounded-2xl shadow-lg border border-base-300 hover:shadow-xl hover:border-green-500/30 transition-all duration-300 group">
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 mx-auto mb-4">
                                <x-mary-icon name="o-globe-alt" class="w-8 h-8 text-white" />
                            </div>
                            <h3 class="text-lg font-bold text-base-content mb-2 transition-colors duration-300"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                Website
                            </h3>
                            <a href="https://{{ $schoolWebsite }}" target="_blank"
                                class="text-green-600 hover:text-green-700 font-medium text-sm transition-colors duration-200"
                                style="font-family: 'Inter', sans-serif;">
                                {{ $schoolWebsite }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Google Maps Section -->
                <div class="max-w-4xl mx-auto">
                    <div
                        class="bg-base-100 p-6 sm:p-8 rounded-2xl shadow-lg border border-base-300 transition-all duration-300 hover:shadow-xl">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
