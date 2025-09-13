<div>
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Kelola Carousel Beranda
        </h1>
        <x-mary-button label="Tambah Carousel" class="btn-primary" link="{{ route('admin.home-carousels.create') }}" />
    </div>

    <!-- Flash Message -->
    @if (session('message'))
        <x-mary-alert class="mb-4 alert-success">
            {{ session('message') }}
        </x-mary-alert>
    @endif

    <!-- Search & Filter Section -->
    <div class="mb-6">
        <x-mary-input wire:model.live.debounce.300ms="search" placeholder="Cari carousel..." class="w-full md:w-80" />
    </div>

    <!-- Carousel Table -->
    <x-mary-card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Gambar</th>
                        <th scope="col" class="px-6 py-3">Judul</th>
                        <th scope="col" class="px-6 py-3">Urutan</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($carousels as $carousel)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4">
                                @if ($carousel->gambar)
                                    <picture>
                                        <source srcset="{{ $carousel->gambar_webp_url }}" type="image/webp">
                                        <img src="{{ $carousel->gambar_url }}" alt="{{ $carousel->judul }}"
                                            class="w-32 h-16 object-cover rounded" loading="lazy"
                                            onerror="this.src='/images/placeholder-image.jpg'; this.onerror='';">
                                    </picture>
                                @else
                                    <img src="/images/placeholder-image.jpg" alt="No Image"
                                        class="w-32 h-16 object-cover rounded opacity-50">
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $carousel->judul }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $carousel->urutan }}
                            </td>
                            <td class="px-6 py-4">
                                <x-mary-toggle wire:click="toggleActive('{{ $carousel->id }}')" :checked="$carousel->aktif"
                                    class="toggle-success" />
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <x-mary-button wire:navigate
                                        link="{{ route('admin.home-carousels.edit', $carousel->id) }}"
                                        icon="o-pencil-square" class="btn-sm btn-warning" tooltip="Edit" />
                                    <x-mary-button wire:click="delete('{{ $carousel->id }}')"
                                        wire:confirm="Apakah Anda yakin ingin menghapus carousel ini?" icon="o-trash"
                                        class="btn-sm btn-error" tooltip="Hapus" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center">
                                Tidak ada data carousel
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $carousels->links() }}
        </div>
    </x-mary-card>
</div>
