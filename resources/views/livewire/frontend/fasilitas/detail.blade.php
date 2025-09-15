<div class="min-h-screen bg-base-100">
    <!-- Breadcrumb Navigation -->
    <div class="bg-base-200 py-4">
        <div class="container mx-auto px-4">
            <div class="breadcrumbs text-sm" style="font-family: 'Inter', sans-serif;">
                <ul>
                    <li><a href="{{ route('home') }}" class="text-primary hover:text-primary-focus">Beranda</a></li>
                    <li><a href="{{ route('fasilitas.index') }}"
                            class="text-primary hover:text-primary-focus">Fasilitas</a></li>
                    <li class="text-base-content/70">{{ $facility->nama }}</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <button wire:click="backToIndex" class="btn btn-ghost btn-sm text-primary hover:text-primary-focus">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali ke Fasilitas
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Facility Header -->
                <div class="space-y-4">
                    <h1 class="text-4xl font-bold text-base-content"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $facility->nama }}
                    </h1>

                    <div class="flex flex-wrap gap-3">
                        <!-- Category Badge -->
                        <div class="badge badge-primary badge-lg">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            {{ ucfirst($facility->kategori) }}
                        </div>

                        <!-- Study Program Badge -->
                        @if ($facility->studyProgram)
                            <div class="badge badge-secondary badge-lg">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z">
                                    </path>
                                </svg>
                                {{ $facility->studyProgram->nama }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Image Gallery -->
                @php
                    $allImages = $facility->getAllImageUrls();
                    $imageCount = count($allImages);
                @endphp

                @if ($imageCount > 0)
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body p-0">
                            @if ($imageCount > 1)
                                <!-- Multiple Images Carousel -->
                                <div class="carousel w-full rounded-t-2xl" x-data="{ currentSlide: 0, totalSlides: {{ $imageCount }} }">
                                    @foreach ($allImages as $index => $imageUrl)
                                        <div class="carousel-item relative w-full {{ $index === 0 ? 'block' : 'hidden' }}"
                                            x-show="currentSlide === {{ $index }}"
                                            x-transition:enter="transition ease-out duration-500"
                                            x-transition:enter-start="opacity-0 transform scale-95"
                                            x-transition:enter-end="opacity-100 transform scale-100">
                                            <img src="{{ $imageUrl }}"
                                                alt="{{ $facility->nama }} - Gambar {{ $index + 1 }}"
                                                class="w-full h-96 object-cover">
                                        </div>
                                    @endforeach

                                    <!-- Navigation Controls -->
                                    <div class="absolute inset-0 flex items-center justify-between p-4">
                                        <button
                                            @click="currentSlide = currentSlide > 0 ? currentSlide - 1 : totalSlides - 1"
                                            class="btn btn-circle bg-black/50 border-none text-white hover:bg-black/70">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>
                                        <button
                                            @click="currentSlide = currentSlide < totalSlides - 1 ? currentSlide + 1 : 0"
                                            class="btn btn-circle bg-black/50 border-none text-white hover:bg-black/70">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Slide Indicators -->
                                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                        @foreach ($allImages as $index => $imageUrl)
                                            <button @click="currentSlide = {{ $index }}"
                                                class="w-3 h-3 rounded-full transition-all duration-200"
                                                :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/50'"></button>
                                        @endforeach
                                    </div>

                                    <!-- Image Counter -->
                                    <div class="absolute top-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm"
                                        style="font-family: 'Inter', sans-serif;">
                                        <span x-text="currentSlide + 1"></span> / {{ $imageCount }}
                                    </div>
                                </div>
                            @else
                                <!-- Single Image -->
                                <figure>
                                    <img src="{{ $allImages[0] }}" alt="{{ $facility->nama }}"
                                        class="w-full h-96 object-cover rounded-t-2xl">
                                </figure>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Facility Description -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                            <svg class="w-6 h-6 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Deskripsi Fasilitas
                        </h2>
                        <div class="prose max-w-none" style="font-family: 'Inter', sans-serif;">
                            {!! nl2br(e($facility->deskripsi)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Facility Info Card -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h3 class="card-title text-lg mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                            <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Informasi Fasilitas
                        </h3>

                        <div class="space-y-3" style="font-family: 'Inter', sans-serif;">
                            <div class="flex justify-between items-center">
                                <span class="text-base-content/70">Kategori:</span>
                                <span class="font-medium">{{ ucfirst($facility->kategori) }}</span>
                            </div>

                            @if ($facility->studyProgram)
                                <div class="flex justify-between items-center">
                                    <span class="text-base-content/70">Program Studi:</span>
                                    <span class="font-medium">{{ $facility->studyProgram->nama }}</span>
                                </div>
                            @endif


                        </div>
                    </div>
                </div>

                <!-- Related Facilities -->
                @if ($relatedFacilities->count() > 0)
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body">
                            <h3 class="card-title text-lg mb-4"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Fasilitas Terkait
                            </h3>

                            <div class="space-y-3">
                                @foreach ($relatedFacilities as $related)
                                    <div class="card card-compact bg-base-200 hover:bg-base-300 transition-colors cursor-pointer"
                                        wire:click="goToFacility('{{ $related->id }}')">
                                        <div class="card-body">
                                            <div class="flex items-center space-x-3">
                                                @php
                                                    $relatedImages = $related->getAllImageUrls();
                                                @endphp

                                                @if (count($relatedImages) > 0)
                                                    <div class="avatar">
                                                        <div class="w-12 h-12 rounded-lg">
                                                            <img src="{{ $relatedImages[0] }}"
                                                                alt="{{ $related->nama }}">
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="avatar placeholder">
                                                        <div
                                                            class="bg-primary text-primary-content rounded-lg w-12 h-12">
                                                            <svg class="w-6 h-6" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="flex-1">
                                                    <h4 class="font-medium text-sm"
                                                        style="font-family: 'Inter', sans-serif;">
                                                        {{ $related->nama }}
                                                    </h4>
                                                    <p class="text-xs text-base-content/70">
                                                        {{ $related->studyProgram ? $related->studyProgram->nama : 'Umum' }}
                                                    </p>
                                                </div>

                                                <svg class="w-4 h-4 text-base-content/50" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
