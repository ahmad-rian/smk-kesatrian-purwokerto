<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Tambah Carousel Baru</h1>
            <p class="text-base-content/70 mt-1">Buat carousel baru untuk halaman beranda</p>
        </div>
        <x-mary-button label="Kembali" class="btn-ghost w-full sm:w-auto"
            link="{{ route('admin.home-carousels.index') }}" />
    </div>

    <!-- Flash Messages -->
    @if (session('message'))
        <x-mary-alert class="alert-success mb-6" icon="o-check-circle">
            {{ session('message') }}
        </x-mary-alert>
    @endif

    @if (session('error'))
        <x-mary-alert class="alert-error mb-6" icon="o-exclamation-triangle">
            {{ session('error') }}
        </x-mary-alert>
    @endif

    <x-mary-card>
        <form wire:submit="save" class="space-y-6">
            <!-- Judul Carousel -->
            <x-mary-input wire:model="judul" label="Judul Carousel *" placeholder="Masukkan judul carousel"
                hint="Judul yang akan ditampilkan di carousel" required />

            <!-- Upload Gambar -->
            <div class="space-y-4">
                <x-mary-file wire:model="gambar" label="Gambar Carousel *" accept="image/*" required
                    hint="Format: JPG, PNG, GIF. Ukuran maks: 2MB. Rekomendasi ukuran: 1920x600 pixel." />

                <!-- Preview Gambar -->
                @if ($gambar)
                    <div>
                        <label class="block text-sm font-medium text-base-content mb-2">Preview Gambar:</label>
                        <div class="relative inline-block">
                            <img src="{{ $gambar->temporaryUrl() }}"
                                class="max-w-md w-full h-auto rounded-lg shadow-sm border border-gray-200"
                                alt="Preview">
                            <x-mary-button wire:click="$set('gambar', null)"
                                class="btn-sm btn-circle btn-error absolute -top-2 -right-2" tooltip="Hapus gambar">
                                ×
                            </x-mary-button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Urutan -->
            <x-mary-input wire:model="urutan" label="Urutan Tampil *" type="number" min="1" required
                hint="Urutan penampilan carousel (1 = pertama, 2 = kedua, dst)" class="w-32" />

            <!-- Status Aktif -->
            <x-mary-checkbox wire:model="aktif" label="Aktif"
                hint="Carousel akan ditampilkan di halaman beranda jika dicentang" />

            <!-- Tombol Submit -->
            <div class="flex flex-col sm:flex-row sm:justify-end gap-3">
                <x-mary-button label="Batal" link="{{ route('admin.home-carousels.index') }}"
                    class="btn-ghost w-full sm:w-auto" />

                <x-mary-button label="Simpan Carousel" type="submit" class="btn-primary w-full sm:w-auto"
                    spinner="save" />
            </div>
        </form>
    </x-mary-card>
</div>
