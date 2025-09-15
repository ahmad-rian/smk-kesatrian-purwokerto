<div>
    {{-- Header Section --}}
    <x-mary-header title="Detail Fasilitas" separator>
        <x-slot:middle class="!justify-end">
            <x-mary-breadcrumbs class="text-sm">
                <x-mary-breadcrumb label="Dashboard" link="{{ route('admin.dashboard') }}" />
                <x-mary-breadcrumb label="Fasilitas" link="{{ route('admin.facilities.index') }}" />
                <x-mary-breadcrumb label="{{ $facility->nama }}" />
            </x-mary-breadcrumbs>
        </x-slot:middle>
        <x-slot:actions>
            <x-mary-button label="Kembali" icon="o-arrow-left" wire:click="backToIndex" class="btn-outline" />
            <x-mary-button label="Edit" icon="o-pencil-square" wire:click="edit" class="btn-primary" />
            <x-mary-button label="Hapus" icon="o-trash" wire:click="confirmDelete" class="btn-error" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left Column: Facility Image --}}
        <div class="lg:col-span-1">
            <x-mary-card title="Gambar Fasilitas" class="h-fit">
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                    @if ($facility->gambar)
                        <img src="{{ $facility->gambar_url }}" alt="{{ $facility->nama }}"
                            class="w-full h-full object-cover"
                            onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBmaWxsPSIjRjNGNEY2Ii8+CjxwYXRoIGQ9Ik0yMCAyNkM5IDI2IDkgMTQgMjAgMTRTMzEgMjYgMjAgMjZaIiBmaWxsPSIjOUNBM0FGIi8+CjxjaXJjbGUgY3g9IjIwIiBjeT0iMTgiIHI9IjMiIGZpbGw9IiM2QjcyODAiLz4KPC9zdmc+'" />
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <div class="text-center">
                                <x-mary-icon name="o-photo" class="w-16 h-16 text-gray-400 mx-auto mb-2" />
                                <p class="text-gray-500 text-sm">Tidak ada gambar</p>
                            </div>
                        </div>
                    @endif
                </div>

                @if ($facility->gambar)
                    <div class="mt-4 text-center">
                        <x-mary-button label="Lihat Gambar Penuh"
                            onclick="document.getElementById('image-modal').showModal()" class="btn-outline btn-sm" />
                    </div>
                @endif
            </x-mary-card>
        </div>

        {{-- Right Column: Facility Details --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Basic Information --}}
            <x-mary-card title="Informasi Dasar">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas</label>
                            <div class="text-lg font-semibold text-gray-900">{{ $facility->nama }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            @if ($facility->kategori)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                                <span class="text-gray-400">Tidak ada kategori</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi</label>
                        @if ($facility->studyProgram)
                            <div class="flex items-center gap-2">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                    {{ $facility->studyProgram->nama }}
                                </span>
                                <span class="text-sm text-gray-500">({{ $facility->studyProgram->kode }})</span>
                            </div>
                        @else
                            <span class="text-gray-400">Tidak terkait program studi</span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <div class="text-gray-900 leading-relaxed">
                            {!! nl2br(e($facility->deskripsi)) !!}
                        </div>
                    </div>
                </div>
            </x-mary-card>

            {{-- Metadata Information --}}
            <x-mary-card title="Informasi Sistem">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ID Fasilitas</label>
                        <div class="text-sm text-gray-600 font-mono">{{ $facility->id }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Gambar</label>
                        @if ($facility->gambar)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <x-mary-icon name="o-check-circle" class="w-3 h-3 mr-1" />
                                Ada Gambar
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <x-mary-icon name="o-exclamation-triangle" class="w-3 h-3 mr-1" />
                                Belum Ada Gambar
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat</label>
                        <div class="text-sm text-gray-600">{{ $facility->created_at->format('d M Y, H:i') }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diupdate</label>
                        <div class="text-sm text-gray-600">{{ $facility->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </x-mary-card>
        </div>
    </div>

    {{-- Image Modal --}}
    @if ($facility->gambar)
        <dialog id="image-modal" class="modal">
            <div class="modal-box max-w-4xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="font-bold text-lg mb-4">{{ $facility->nama }}</h3>
                <img src="{{ $facility->gambar_url }}" alt="{{ $facility->nama }}" class="w-full h-auto rounded-lg" />
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    @endif

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="facilityToDelete" title="Konfirmasi Penghapusan"
        subtitle="Apakah Anda yakin ingin menghapus fasilitas ini?">
        @if ($facilityToDelete)
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

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="cancelDelete" />
            <x-mary-button label="Ya, Hapus" class="btn-error" wire:click="deleteFacility" />
        </x-slot:actions>
    </x-mary-modal>
</div>
