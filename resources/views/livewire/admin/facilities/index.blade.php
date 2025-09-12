<div>
    {{-- Header Section --}}
    <x-mary-header title="Manajemen Fasilitas" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-mary-input placeholder="Cari fasilitas..." wire:model.live.debounce="search" clearable
                icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button label="Tambah Fasilitas" icon="o-plus" class="btn-primary"
                link="{{ route('admin.facilities.create') }}" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-mary-stat title="Total Fasilitas" description="Semua fasilitas" value="{{ $stats['total'] }}"
            icon="o-building-office-2" color="text-blue-500" />

        <x-mary-stat title="Dengan Gambar" description="Fasilitas bergambar" value="{{ $stats['with_image'] }}"
            icon="o-photo" color="text-green-500" />

        <x-mary-stat title="Tanpa Gambar" description="Perlu gambar" value="{{ $stats['without_image'] }}"
            icon="o-x-circle" color="text-orange-500" />

        <x-mary-stat title="Program Studi" description="Jumlah program" value="{{ count($stats['by_program']) }}"
            icon="o-academic-cap" color="text-purple-500" />
    </div>

    {{-- Filters Section --}}
    <x-mary-card class="mb-6">
        <div class="flex flex-wrap gap-4 items-end">
            {{-- Filter Program Studi --}}
            <div class="flex-1 min-w-48">
                <x-mary-select label="Filter Program Studi" wire:model.live="study_program_filter" :options="$studyPrograms"
                    option-value="id" option-label="nama" placeholder="Semua Program Studi" clearable />
            </div>

            {{-- Items per page --}}
            <div class="w-32">
                <x-mary-select label="Per Halaman" wire:model.live="perPage" :options="collect($perPageOptions)->map(fn($val) => ['id' => $val, 'name' => $val])" option-value="id"
                    option-label="name" />
            </div>

            {{-- Sort By --}}
            <div class="w-48">
                <x-mary-select label="Urutkan" wire:model.live="sortBy" :options="collect($sortableColumns)->map(fn($label, $key) => ['id' => $key, 'name' => $label])" option-value="id"
                    option-label="name" />
            </div>

            {{-- Sort Direction --}}
            <div class="w-32">
                <x-mary-select label="Arah" wire:model.live="sortDirection" :options="[['id' => 'asc', 'name' => 'A-Z'], ['id' => 'desc', 'name' => 'Z-A']]" option-value="id"
                    option-label="name" />
            </div>

            {{-- Reset Button --}}
            <x-mary-button label="Reset" icon="o-arrow-path" wire:click="resetFilters" class="btn-outline" />
        </div>
    </x-mary-card>

    {{-- Table Section --}}
    <x-mary-card>
        @if ($facilities->count() > 0)
            <x-mary-table :headers="[
                ['key' => 'gambar', 'label' => 'Gambar', 'class' => 'w-20'],
                ['key' => 'nama', 'label' => 'Nama Fasilitas', 'class' => 'w-1/4'],
                ['key' => 'kategori', 'label' => 'Kategori', 'class' => 'w-1/6'],
                ['key' => 'study_program', 'label' => 'Program Studi', 'class' => 'w-1/4'],
                ['key' => 'deskripsi', 'label' => 'Deskripsi', 'class' => 'w-1/4'],
                ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-32'],
            ]" :rows="$facilities" with-pagination>

                {{-- Gambar Column --}}
                @scope('cell_gambar', $facility)
                    <div class="avatar">
                        <div class="w-12 h-12 rounded-lg">
                            @if ($facility->gambar)
                                <img src="{{ $facility->gambar_url }}" alt="{{ $facility->nama }}"
                                    class="object-cover w-full h-full rounded-lg"
                                    onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yMCAyNkM5IDI2IDkgMTQgMjAgMTRTMzEgMjYgMjAgMjZaIiBmaWxsPSIjOUNBM0FGIi8+CjxjaXJjbGUgY3g9IjIwIiBjeT0iMTgiIHI9IjMiIGZpbGw9IiM2QjcyODAiLz4KPC9zdmc+'" />
                            @else
                                <div class="w-full h-full bg-gray-200 rounded-lg flex items-center justify-center">
                                    <x-mary-icon name="o-photo" class="w-6 h-6 text-gray-400" />
                                </div>
                            @endif
                        </div>
                    </div>
                @endscope

                {{-- Nama Column --}}
                @scope('cell_nama', $facility)
                    <div>
                        <div class="font-semibold text-gray-900">{{ $facility->nama }}</div>
                        <div class="text-sm text-gray-500">ID: {{ $facility->id }}</div>
                    </div>
                @endscope

                {{-- Kategori Column --}}
                @scope('cell_kategori', $facility)
                    @if ($facility->kategori)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @switch($facility->kategori)
                                @case('laboratorium')
                                    bg-blue-100 text-blue-800
                                    @break
                                @case('perpustakaan')
                                    bg-green-100 text-green-800
                                    @break
                                @case('olahraga')
                                    bg-orange-100 text-orange-800
                                    @break
                                @case('aula')
                                    bg-purple-100 text-purple-800
                                    @break
                                @case('kantin')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('asrama')
                                    bg-indigo-100 text-indigo-800
                                    @break
                                @case('parkir')
                                    bg-gray-100 text-gray-800
                                    @break
                                @default
                                    bg-gray-100 text-gray-800
                            @endswitch">
                            {{ ucfirst($facility->kategori) }}
                        </span>
                    @else
                        <span class="text-gray-400 text-sm">-</span>
                    @endif
                @endscope

                {{-- Program Studi Column --}}
                @scope('cell_study_program', $facility)
                    @if ($facility->studyProgram)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-black-800">
                            {{ $facility->studyProgram->nama }}
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Tidak ada
                        </span>
                    @endif
                @endscope

                {{-- Deskripsi Column --}}
                @scope('cell_deskripsi', $facility)
                    <div class="text-sm text-gray-600">
                        {{ Str::limit($facility->deskripsi, 100) }}
                    </div>
                @endscope

                {{-- Actions Column --}}
                @scope('cell_actions', $facility)
                    <div class="flex gap-2">
                        <x-mary-button icon="o-pencil-square" link="{{ route('admin.facilities.edit', $facility) }}"
                            class="btn-sm btn-outline" tooltip="Edit Fasilitas" />

                        <x-mary-button icon="o-trash" wire:click="confirmDelete('{{ $facility->id }}')"
                            class="btn-sm btn-outline btn-error" tooltip="Hapus Fasilitas" />
                    </div>
                @endscope
            </x-mary-table>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <x-mary-icon name="o-building-office-2" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada fasilitas</h3>
                <p class="text-gray-500 mb-6">
                    @if ($search || $study_program_filter)
                        Tidak ada fasilitas yang sesuai dengan filter yang dipilih.
                    @else
                        Mulai dengan menambahkan fasilitas pertama untuk sekolah Anda.
                    @endif
                </p>
                @if (!$search && !$study_program_filter)
                    <x-mary-button label="Tambah Fasilitas Pertama" icon="o-plus"
                        link="{{ route('admin.facilities.create') }}" class="btn-primary" />
                @else
                    <x-mary-button label="Reset Filter" icon="o-arrow-path" wire:click="resetFilters"
                        class="btn-outline" />
                @endif
            </div>
        @endif
    </x-mary-card>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="facilityToDelete" title="Konfirmasi Penghapusan"
        subtitle="Apakah Anda yakin ingin menghapus fasilitas ini?">
        @if ($facilityToDelete)
            @php
                $facility = \App\Models\Facility::find($facilityToDelete);
            @endphp
            @if ($facility)
                <div class="mb-4">
                    <div class="flex items-center gap-4 p-4 bg-red-50 rounded-lg">
                        @if ($facility->gambar)
                            <img src="{{ $facility->gambar_url }}" alt="{{ $facility->nama }}"
                                class="w-16 h-16 object-cover rounded-lg">
                        @else
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                <x-mary-icon name="o-photo" class="w-8 h-8 text-gray-400" />
                            </div>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $facility->nama }}</h4>
                            <p class="text-sm text-gray-600">{{ $facility->studyProgram?->nama }}</p>
                            <p class="text-sm text-gray-500">{{ Str::limit($facility->deskripsi, 80) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                    <div class="flex items-start">
                        <x-mary-icon name="o-exclamation-triangle" class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" />
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">Peringatan:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Data fasilitas akan dihapus permanen</li>
                                <li>Gambar fasilitas akan dihapus dari server</li>
                                <li>Aksi ini tidak dapat dibatalkan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="cancelDelete" />
            <x-mary-button label="Ya, Hapus" class="btn-error" wire:click="deleteFacility" />
        </x-slot:actions>
    </x-mary-modal>
</div>
