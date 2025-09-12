<div>
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="title text-2xl font-bold text-base-content">Program Studi</h1>
            <p class="body text-base-content/70 mt-1">Kelola data program studi sekolah</p>
        </div>

        <x-mary-button label="Tambah Program Studi" icon="o-plus" class="btn-primary"
            link="{{ route('admin.study-programs.create') }}" />
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <x-mary-stat title="Total Program" description="Semua program studi" value="{{ $stats['total'] }}"
            icon="o-academic-cap" color="text-blue-500" />

        <x-mary-stat title="Program Aktif" description="Program yang sedang berjalan" value="{{ $stats['active'] }}"
            icon="o-check-circle" color="text-green-500" />

        <x-mary-stat title="Program Nonaktif" description="Program yang tidak aktif" value="{{ $stats['inactive'] }}"
            icon="o-x-circle" color="text-red-500" />
    </div>

    {{-- Filters Section --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search Input --}}
                <x-mary-input wire:model.live.debounce.300ms="search"
                    placeholder="Cari berdasarkan kode, nama, atau ketua program..." icon="o-magnifying-glass"
                    clearable />

                {{-- Status Filter --}}
                <x-mary-select wire:model.live="statusFilter" placeholder="Filter Status" icon="o-funnel"
                    :options="[
                        ['id' => 'all', 'name' => 'Semua Status'],
                        ['id' => 'active', 'name' => 'Aktif'],
                        ['id' => 'inactive', 'name' => 'Nonaktif'],
                    ]" />

                {{-- Sort Options --}}
                <x-mary-select wire:model.live="sortBy" placeholder="Urutkan Berdasarkan" icon="o-bars-3-bottom-left"
                    :options="[
                        ['id' => 'urutan', 'name' => 'Urutan'],
                        ['id' => 'nama', 'name' => 'Nama'],
                        ['id' => 'kode', 'name' => 'Kode'],
                        ['id' => 'created_at', 'name' => 'Tanggal Dibuat'],
                    ]" />
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th class="w-16">#</th>
                            <th class="w-20">Gambar</th>
                            <th class="w-24">Kode</th>
                            <th>Nama Program</th>
                            <th>Ketua Program</th>
                            <th class="w-24">Status</th>
                            <th class="w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studyPrograms as $studyProgram)
                            <tr>

                                {{-- Urutan Column --}}
                                <td>
                                    <div class="flex items-center justify-center">
                                        <div class="badge badge-ghost">{{ $studyProgram->urutan }}</div>
                                    </div>
                                </td>

                                {{-- Gambar Column --}}
                                <td>
                                    <div class="flex items-center justify-center">
                                        @if ($studyProgram->gambar)
                                            <img src="{{ $studyProgram->gambar_url }}" alt="{{ $studyProgram->nama }}"
                                                class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                        @else
                                            <div
                                                class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                                <x-mary-icon name="o-photo" class="w-6 h-6 text-gray-400" />
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Kode Column --}}
                                <td>
                                    <div class="badge badge-outline">{{ $studyProgram->kode }}</div>
                                </td>

                                {{-- Nama Column --}}
                                <td>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $studyProgram->nama }}</div>
                                        @if ($studyProgram->deskripsi)
                                            <div class="text-sm text-gray-500 line-clamp-2">
                                                {{ Str::limit($studyProgram->deskripsi, 80) }}</div>
                                        @endif
                                        @if ($studyProgram->warna)
                                            <div class="flex items-center mt-1">
                                                <div class="w-4 h-4 rounded-full border border-gray-200 mr-2"
                                                    style="background-color: {{ $studyProgram->warna }}"></div>
                                                <span class="text-xs text-gray-500">{{ $studyProgram->warna }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                {{-- Ketua Program Column --}}
                                <td>
                                    @if ($studyProgram->ketua_program)
                                        <div class="text-sm text-gray-900">{{ $studyProgram->ketua_program }}</div>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Belum ditentukan</span>
                                    @endif
                                </td>

                                {{-- Status Column --}}
                                <td>
                                    <div class="flex items-center justify-center">
                                        <x-mary-toggle wire:click="toggleStatus('{{ $studyProgram->id }}')"
                                            :checked="$studyProgram->aktif" class="toggle-success" />
                                    </div>
                                </td>

                                {{-- Actions Column --}}
                                <td>
                                    <div class="flex items-center gap-2">
                                        <x-mary-button icon="o-pencil-square"
                                            link="{{ route('admin.study-programs.edit', $studyProgram) }}"
                                            class="btn-ghost btn-sm" tooltip="Edit" />

                                        <x-mary-button icon="o-trash"
                                            wire:click="confirmDelete('{{ $studyProgram->id }}')"
                                            class="btn-ghost btn-sm text-red-500 hover:text-red-700" tooltip="Hapus" />
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12">
                                    <x-mary-icon name="o-academic-cap" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada program studi</h3>
                                    <p class="text-gray-500 mb-4">Mulai dengan menambahkan program studi pertama.</p>
                                    <x-mary-button label="Tambah Program Studi" icon="o-plus" class="btn-primary"
                                        link="{{ route('admin.study-programs.create') }}" />
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $studyPrograms->links() }}
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus"
        subtitle="Apakah Anda yakin ingin menghapus program studi ini?">
        @if ($deleteId)
            @php
                $programToDelete = $studyPrograms->firstWhere('id', $deleteId);
            @endphp
            @if ($programToDelete)
                <div class="mb-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <x-mary-icon name="o-exclamation-triangle" class="w-6 h-6 text-red-500 mr-3" />
                            <div>
                                <h4 class="font-medium text-red-800">{{ $programToDelete->nama }}</h4>
                                <p class="text-sm text-red-600">Kode: {{ $programToDelete->kode }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">
                        Data yang dihapus tidak dapat dikembalikan. Pastikan Anda sudah yakin dengan keputusan ini.
                    </p>
                </div>
            @endif
        @endif

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="cancelDelete" class="btn-ghost" />
            <x-mary-button label="Hapus" wire:click="delete" class="btn-error" />
        </x-slot:actions>
    </x-mary-modal>
</div>
