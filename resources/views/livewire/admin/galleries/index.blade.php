<div>
    {{-- Header Section --}}
    <x-mary-header title="Kelola Gallery" subtitle="Kelola gallery foto dan gambar untuk website sekolah" separator progress-indicator>
        <x-slot:actions>
            <x-mary-button label="Tambah Gallery" icon="o-plus" class="btn-primary"
                link="{{ route('admin.galleries.create') }}" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-mary-stat title="Total Gallery" :value="$this->totalGalleries" icon="o-photo" color="text-blue-600"
            class="bg-white dark:bg-gray-800 shadow-sm" />

        <x-mary-stat title="Gallery Aktif" :value="$this->activeGalleries" icon="o-check-circle" color="text-green-600"
            class="bg-white dark:bg-gray-800 shadow-sm" />

        <x-mary-stat title="Gallery Nonaktif" :value="$this->inactiveGalleries" icon="o-x-circle" color="text-red-600"
            class="bg-white dark:bg-gray-800 shadow-sm" />

        <x-mary-stat title="Total Gambar" :value="$this->totalImages" icon="o-camera" color="text-purple-600"
            class="bg-white dark:bg-gray-800 shadow-sm" />
    </div>

    {{-- Filters Section --}}
    <x-mary-card class="mb-6">
        <div class="flex flex-wrap gap-4 items-end">
            {{-- Search Input --}}
            <div class="flex-1 min-w-48">
                <x-mary-input label="Pencarian" wire:model.live.debounce="search"
                    placeholder="Cari berdasarkan judul atau deskripsi..." icon="o-magnifying-glass" clearable />
            </div>
            
            {{-- Status Filter --}}
            <div class="w-48">
                <x-mary-select label="Status" wire:model.live="statusFilter" :options="[
                    ['value' => 'all', 'label' => 'Semua Status'],
                    ['value' => 'active', 'label' => 'Aktif'],
                    ['value' => 'inactive', 'label' => 'Nonaktif'],
                ]" option-value="value"
                    option-label="label" />
            </div>

            {{-- Items per page --}}
            <div class="w-32">
                <x-mary-select label="Per Halaman" wire:model.live="perPage" :options="[5, 10, 25, 50]" />
            </div>

            {{-- Sort By --}}
            <div class="w-48">
                <x-mary-select label="Urutkan" wire:model.live="sortBy" :options="[
                    ['value' => 'urutan', 'label' => 'Urutan'],
                    ['value' => 'judul', 'label' => 'Judul'],
                    ['value' => 'created_at', 'label' => 'Tanggal Dibuat'],
                ]" option-value="value" option-label="label" />
            </div>

            {{-- Sort Direction --}}
            <div class="w-48">
                <x-mary-select label="Arah" wire:model.live="sortDirection" :options="[
                    ['value' => 'asc', 'label' => 'Naik (A-Z)'],
                    ['value' => 'desc', 'label' => 'Turun (Z-A)'],
                ]" option-value="value" option-label="label" />
            </div>

            {{-- Reset Button --}}
            <div>
                <x-mary-button label="Reset" icon="o-arrow-path" wire:click="resetFilters" class="btn-outline" />
            </div>
        </div>
    </x-mary-card>

    {{-- Table Section --}}
    <x-mary-card>
        @if ($galleries->count() > 0)
            <x-mary-table :headers="[
                ['key' => 'gallery', 'label' => 'Gallery', 'class' => 'w-1/4'],
                ['key' => 'gambar', 'label' => 'Gambar', 'class' => 'w-1/6'],
                ['key' => 'status', 'label' => 'Status', 'class' => 'w-1/6'],
                ['key' => 'urutan', 'label' => 'Urutan', 'class' => 'w-1/6'],
                ['key' => 'tanggal', 'label' => 'Tanggal', 'class' => 'w-1/6'],
                ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32'],
            ]" :rows="$galleries" with-pagination>

                {{-- Gallery Info Column --}}
                @scope('cell_gallery', $gallery)
                    <div class="flex items-center space-x-3">
                        {{-- Gambar Sampul --}}
                        <div class="flex-shrink-0">
                            <div class="avatar">
                                <div class="w-12 h-12 rounded-lg">
                                    @if ($gallery->gambar_sampul)
                                        <img src="{{ Storage::url($gallery->gambar_sampul) }}" alt="{{ $gallery->judul }}"
                                            class="object-cover w-full h-full rounded-lg" 
                                            onerror="this.onerror=null; this.src='/images/placeholder-image.svg'; console.error('Gambar tidak dapat dimuat:', this.alt);" />
                                    @else
                                        <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                            <x-mary-icon name="o-photo" class="w-6 h-6 text-gray-400" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">{{ $gallery->judul }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($gallery->deskripsi, 50) }}</div>
                        </div>
                    </div>
                @endscope

                {{-- Jumlah Gambar Column --}}
                @scope('cell_gambar', $gallery)
                    <div class="flex items-center space-x-2">
                        <x-mary-icon name="o-camera" class="w-4 h-4 text-gray-400" />
                        <span class="text-sm text-gray-900">
                            {{ $gallery->images_count ?? 0 }} gambar
                        </span>
                    </div>
                @endscope

                {{-- Status Column --}}
                @scope('cell_status', $gallery)
                    <x-mary-toggle wire:model.live="galleries.{{ $loop->index }}.aktif"
                        wire:change="toggleStatus('{{ $gallery->id }}')" :checked="$gallery->aktif" />
                @endscope

                {{-- Urutan Column --}}
                @scope('cell_urutan', $gallery)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $gallery->urutan }}
                    </span>
                @endscope

                {{-- Tanggal Column --}}
                @scope('cell_tanggal', $gallery)
                    <div>
                        <p>{{ $gallery->created_at->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $gallery->created_at->format('H:i') }}</p>
                    </div>
                @endscope

                {{-- Actions Column --}}
                @scope('cell_actions', $gallery)
                    <div class="flex gap-2">
                        <x-mary-button icon="o-pencil-square" link="{{ route('admin.galleries.edit', $gallery->id) }}"
                            class="btn-sm btn-outline" tooltip="Edit Gallery" />

                        <x-mary-button icon="o-trash" wire:click="confirmDelete('{{ $gallery->id }}')"
                            class="btn-sm btn-outline btn-error" tooltip="Hapus Gallery" />
                    </div>
                @endscope
            </x-mary-table>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <x-mary-icon name="o-photo" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-semibold text-gray-900">
                    Belum ada galeri
                </h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if ($search || $statusFilter !== 'all')
                        Tidak ada galeri yang sesuai dengan filter pencarian.
                    @else
                        Mulai dengan menambahkan galeri baru.
                    @endif
                </p>
                @if (!$search && $statusFilter === 'all')
                    <div class="mt-6">
                        <x-mary-button icon="o-plus" link="{{ route('admin.galleries.create') }}" class="btn-primary">
                            Tambah Galeri
                        </x-mary-button>
                    </div>
                @endif
            </div>
        @endif
    </x-mary-card>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus" class="backdrop-blur">
        <div class="py-4">
            <div class="flex items-center space-x-3 mb-4">
                <div class="flex-shrink-0">
                    <x-mary-icon name="o-exclamation-triangle" class="h-8 w-8 text-red-600" />
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        Hapus Gallery
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                        style="font-family: 'Inter', sans-serif;">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <p class="text-sm text-red-800 dark:text-red-200" style="font-family: 'Inter', sans-serif;">
                    Apakah Anda yakin ingin menghapus gallery
                    <strong>"{{ $deleteGalleryName }}"</strong>?
                    <br><br>
                    <span class="font-medium">Peringatan:</span> Semua gambar dalam gallery ini juga akan dihapus
                    secara permanen.
                </p>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="cancelDelete" class="btn-ghost" />
            <x-mary-button label="Hapus" wire:click="delete" class="btn-error" spinner="delete" />
        </x-slot:actions>
    </x-mary-modal>

    {{-- Loading Overlay - Hanya untuk operasi yang membutuhkan waktu lama --}}
    <div wire:loading.flex wire:target="delete" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <x-mary-loading class="loading-spinner loading-md" />
                <span class="text-gray-700 dark:text-gray-300" style="font-family: 'Inter', sans-serif;">
                    Menghapus gallery...
                </span>
            </div>
        </div>
    </div>
</div>
