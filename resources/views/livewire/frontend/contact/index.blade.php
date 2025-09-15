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

    <!-- Contact Form Section -->
    <section class="py-16 bg-base-200/50 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="max-w-7xl mx-auto">


                <!-- Contact Form and Info Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Contact Form -->
                    <div class="lg:col-span-2">
                        <div
                            class="bg-base-100 p-6 sm:p-8 rounded-2xl shadow-lg border border-base-300 hover:shadow-xl transition-all duration-300">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 sm:w-12 sm:h-12 bg-green-600 rounded-full flex items-center justify-center mr-4 shadow-md">
                                    <x-mary-icon name="o-paper-airplane" class="w-5 h-5 sm:w-6 sm:h-6 text-white" />
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-bold text-base-content transition-colors duration-300"
                                    style="font-family: 'Bricolage Grotesque', sans-serif;">
                                    Kirim Pesan
                                </h2>
                            </div>

                            <!-- Success Message -->
                            @if ($successMessage)
                                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 text-green-600 rounded-lg transition-colors duration-300"
                                    x-data="{ show: true }" x-show="show"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-90"
                                    x-transition:enter-end="opacity-100 transform scale-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <x-mary-icon name="o-check-circle" class="w-5 h-5 mr-2" />
                                            <span class="text-base-content transition-colors duration-300"
                                                style="font-family: 'Inter', sans-serif;">{{ $successMessage }}</span>
                                        </div>
                                        <button @click="show = false; $wire.resetSuccessMessage()"
                                            class="text-green-600 hover:text-green-700 transition-colors duration-200">
                                            <x-mary-icon name="o-x-mark" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            @endif

                            <!-- Error Messages -->
                            @if (session()->has('error'))
                                <div
                                    class="mb-6 p-4 bg-red-500/10 border border-red-500/30 text-red-600 rounded-lg transition-colors duration-300">
                                    <div class="flex items-center">
                                        <x-mary-icon name="o-exclamation-circle" class="w-5 h-5 mr-2" />
                                        <span class="text-base-content transition-colors duration-300"
                                            style="font-family: 'Inter', sans-serif;">{{ session('error') }}</span>
                                    </div>
                                </div>
                            @endif

                            <form wire:submit="submitContact" class="space-y-6">
                                <!-- Name Field -->
                                <div>
                                    <label for="name"
                                        class="block text-sm font-semibold text-base-content mb-2 transition-colors duration-300"
                                        style="font-family: 'Inter', sans-serif;">
                                        Nama Lengkap *
                                    </label>
                                    <input type="text" id="name" wire:model="name"
                                        class="w-full px-4 py-3 border border-base-300 bg-base-100 text-base-content rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                        placeholder="Masukkan nama lengkap Anda"
                                        style="font-family: 'Inter', sans-serif;">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 transition-colors duration-300"
                                            style="font-family: 'Inter', sans-serif;">
                                            {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email Field -->
                                <div>
                                    <label for="email"
                                        class="block text-sm font-semibold text-base-content mb-2 transition-colors duration-300"
                                        style="font-family: 'Inter', sans-serif;">
                                        Email *
                                    </label>
                                    <input type="email" id="email" wire:model="email"
                                        class="w-full px-4 py-3 border border-base-300 bg-base-100 text-base-content rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                        placeholder="Masukkan alamat email Anda"
                                        style="font-family: 'Inter', sans-serif;">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 transition-colors duration-300"
                                            style="font-family: 'Inter', sans-serif;">
                                            {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone Field -->
                                <div>
                                    <label for="phone"
                                        class="block text-sm font-semibold text-base-content mb-2 transition-colors duration-300"
                                        style="font-family: 'Inter', sans-serif;">
                                        Nomor Telepon
                                    </label>
                                    <input type="tel" id="phone" wire:model="phone"
                                        class="w-full px-4 py-3 border border-base-300 bg-base-100 text-base-content rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                        placeholder="Masukkan nomor telepon Anda"
                                        style="font-family: 'Inter', sans-serif;">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 transition-colors duration-300"
                                            style="font-family: 'Inter', sans-serif;">
                                            {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Subject Field -->
                                <div>
                                    <label for="subject"
                                        class="block text-sm font-semibold text-base-content mb-2 transition-colors duration-300"
                                        style="font-family: 'Inter', sans-serif;">
                                        Subjek *
                                    </label>
                                    <input type="text" id="subject" wire:model="subject"
                                        class="w-full px-4 py-3 border border-base-300 bg-base-100 text-base-content rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200"
                                        placeholder="Masukkan subjek pesan" style="font-family: 'Inter', sans-serif;">
                                    @error('subject')
                                        <p class="mt-1 text-sm text-red-600 transition-colors duration-300"
                                            style="font-family: 'Inter', sans-serif;">
                                            {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Message Field -->
                                <div>
                                    <label for="message"
                                        class="block text-sm font-semibold text-base-content mb-2 transition-colors duration-300"
                                        style="font-family: 'Inter', sans-serif;">
                                        Pesan *
                                    </label>
                                    <textarea id="message" wire:model="message" rows="6"
                                        class="w-full px-4 py-3 border border-base-300 bg-base-100 text-base-content rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200 resize-none"
                                        placeholder="Tulis pesan Anda di sini..." style="font-family: 'Inter', sans-serif;"></textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600 transition-colors duration-300"
                                            style="font-family: 'Inter', sans-serif;">
                                            {{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="w-full bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg"
                                        style="font-family: 'Inter', sans-serif;">
                                        <span wire:loading.remove class="flex items-center">
                                            <x-mary-icon name="o-paper-airplane" class="w-5 h-5 mr-2" />
                                            Kirim Pesan
                                        </span>
                                        <span wire:loading class="flex items-center">
                                            <x-mary-icon name="o-arrow-path" class="w-5 h-5 mr-2 animate-spin" />
                                            Mengirim...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-base-100 rounded-2xl shadow-lg border border-base-300 p-6 transition-all duration-300">
                            <h3 class="text-xl font-bold text-base-content mb-6 transition-colors duration-300"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                Informasi Kontak
                            </h3>

                            <!-- Contact Cards -->
                            <div class="space-y-4">
                                <!-- Address Card -->
                                <div
                                    class="bg-base-200/50 p-4 rounded-xl border border-base-300 hover:shadow-md hover:border-green-500/30 transition-all duration-300 group">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                            <x-mary-icon name="o-map-pin" class="w-5 h-5 text-white" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-base-content mb-1 transition-colors duration-300"
                                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                                Alamat
                                            </h4>
                                            <p class="text-base-content/70 text-xs leading-relaxed transition-colors duration-300"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ $schoolAddress }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Phone Card -->
                                <div
                                    class="bg-base-200/50 p-4 rounded-xl border border-base-300 hover:shadow-md hover:border-green-500/30 transition-all duration-300 group">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                            <x-mary-icon name="o-phone" class="w-5 h-5 text-white" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-base-content mb-1 transition-colors duration-300"
                                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                                Telepon
                                            </h4>
                                            <a href="tel:{{ str_replace(['(', ')', ' ', '-'], '', $schoolPhone) }}"
                                                class="text-green-600 hover:text-green-700 font-medium text-xs transition-colors duration-200"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ $schoolPhone }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email Card -->
                                <div
                                    class="bg-base-200/50 p-4 rounded-xl border border-base-300 hover:shadow-md hover:border-green-500/30 transition-all duration-300 group">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                            <x-mary-icon name="o-envelope" class="w-5 h-5 text-white" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-base-content mb-1 transition-colors duration-300"
                                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                                Email
                                            </h4>
                                            <a href="mailto:{{ $schoolEmail }}"
                                                class="text-green-600 hover:text-green-700 font-medium text-xs transition-colors duration-200"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ $schoolEmail }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Website Card -->
                                <div
                                    class="bg-base-200/50 p-4 rounded-xl border border-base-300 hover:shadow-md hover:border-green-500/30 transition-all duration-300 group">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300 flex-shrink-0">
                                            <x-mary-icon name="o-globe-alt" class="w-5 h-5 text-white" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-bold text-base-content mb-1 transition-colors duration-300"
                                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                                Website
                                            </h4>
                                            <a href="https://{{ $schoolWebsite }}" target="_blank"
                                                class="text-green-600 hover:text-green-700 font-medium text-xs transition-colors duration-200"
                                                style="font-family: 'Inter', sans-serif;">
                                                {{ $schoolWebsite }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
