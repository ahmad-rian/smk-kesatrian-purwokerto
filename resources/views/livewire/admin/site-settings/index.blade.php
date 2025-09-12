<div>
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="title text-2xl font-bold text-base-content">Pengaturan Situs</h1>
            <p class="body text-base-content/70 mt-1">Kelola informasi dasar sekolah dan pengaturan situs</p>
        </div>

        @if ($siteSetting)
            <x-mary-button icon="o-pencil" class="btn-primary" wire:click="edit" spinner="edit">
                Edit Pengaturan
            </x-mary-button>
        @else
            <x-mary-button icon="o-plus" class="btn-primary" wire:click="create" spinner="create">
                Buat Pengaturan
            </x-mary-button>
        @endif
    </div>

    @if ($siteSetting)
        <!-- Site Information Display -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information Card -->
            <div class="card bg-base-100 shadow-xl h-fit">
                <div class="card-body">
                    <h2 class="card-title">Informasi Dasar</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-base-content/70">Nama Sekolah</label>
                            <p class="text-base font-semibold">{{ $siteSetting->nama_sekolah }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-base-content/70">Alamat</label>
                            <p class="text-base">{{ $siteSetting->alamat ?: 'Belum diatur' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-base-content/70">Telepon</label>
                            <p class="text-base">{{ $siteSetting->telepon ?: 'Belum diatur' }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-base-content/70">Email</label>
                            <p class="text-base">{{ $siteSetting->email ?: 'Belum diatur' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Logo & Images Card -->
            <div class="card bg-base-100 shadow-xl h-fit">
                <div class="card-body">
                    <h2 class="card-title">Logo & Gambar</h2>
                    <div class="space-y-4">
                        @if ($siteSetting->logo)
                            <div>
                                <label class="text-sm font-medium text-base-content/70">Logo Sekolah</label>
                                <div class="mt-2">
                                    <img src="{{ Storage::url($siteSetting->logo) }}" alt="Logo Sekolah"
                                        class="w-20 h-20 object-contain bg-base-200 rounded-lg p-2">
                                </div>
                            </div>
                        @endif

                        @if ($siteSetting->foto_kepala_sekolah)
                            <div>
                                <label class="text-sm font-medium text-base-content/70">Foto Kepala Sekolah</label>
                                <div class="mt-2">
                                    <img src="{{ Storage::url($siteSetting->foto_kepala_sekolah) }}"
                                        alt="Foto Kepala Sekolah" class="w-20 h-20 object-cover rounded-lg">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Vision & Mission Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <div class="card bg-base-100 shadow-xl h-fit">
                <div class="card-body">
                    <h2 class="card-title">Visi</h2>
                    <div class="prose prose-sm max-w-none">
                        {!! $siteSetting->visi ? nl2br(e($siteSetting->visi)) : '<em class="text-base-content/50">Belum diatur</em>' !!}
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-xl h-fit">
                <div class="card-body">
                    <h2 class="card-title">Misi</h2>
                    <div class="prose prose-sm max-w-none">
                        {!! $siteSetting->misi ? nl2br(e($siteSetting->misi)) : '<em class="text-base-content/50">Belum diatur</em>' !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Section -->
        @if ($siteSetting->media_sosial && count(array_filter($siteSetting->media_sosial)))
            <div class="card bg-base-100 shadow-xl mt-6">
                <div class="card-body">
                    <h2 class="card-title">Media Sosial</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @if ($siteSetting->instagram)
                            <a href="{{ $siteSetting->instagram }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg transition-all">
                                <x-mary-icon name="o-camera" class="w-5 h-5" />
                                <span class="text-sm font-medium">Instagram</span>
                            </a>
                        @endif

                        @if ($siteSetting->facebook)
                            <a href="{{ $siteSetting->facebook }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-blue-600 text-white rounded-lg hover:shadow-lg transition-all">
                                <x-mary-icon name="o-globe-alt" class="w-5 h-5" />
                                <span class="text-sm font-medium">Facebook</span>
                            </a>
                        @endif

                        @if ($siteSetting->youtube)
                            <a href="{{ $siteSetting->youtube }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-red-600 text-white rounded-lg hover:shadow-lg transition-all">
                                <x-mary-icon name="o-play" class="w-5 h-5" />
                                <span class="text-sm font-medium">YouTube</span>
                            </a>
                        @endif

                        @if ($siteSetting->tiktok)
                            <a href="{{ $siteSetting->tiktok }}" target="_blank"
                                class="flex items-center gap-2 p-3 bg-black text-white rounded-lg hover:shadow-lg transition-all">
                                <x-mary-icon name="o-musical-note" class="w-5 h-5" />
                                <span class="text-sm font-medium">TikTok</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body text-center py-12">
                <div class="max-w-md mx-auto">
                    <x-mary-icon name="o-cog-6-tooth" class="w-16 h-16 mx-auto text-base-content/30 mb-4" />
                    <h3 class="text-lg font-semibold text-base-content mb-2">Belum Ada Pengaturan</h3>
                    <p class="text-base-content/70 mb-6">Mulai dengan membuat pengaturan dasar untuk situs sekolah Anda.
                    </p>
                    <x-mary-button icon="o-plus" class="btn-primary" wire:click="create" spinner="create">
                        Buat Pengaturan Pertama
                    </x-mary-button>
                </div>
            </div>
        </div>
    @endif
</div>
