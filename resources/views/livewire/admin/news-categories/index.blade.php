<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Kategori Berita
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Kelola kategori untuk mengorganisir berita
            </p>
        </div>

        <div class="flex gap-2">
            <x-mary-button wire:navigate href="{{ route('admin.news-categories.create') }}" icon="o-plus"
                class="btn-primary">
                Tambah Kategori
            </x-mary-button>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div>
                <x-mary-input wire:model.live.debounce.300ms="search" placeholder="Cari kategori..."
                    icon="o-magnifying-glass" />
            </div>

            {{-- Status Filter --}}
            {{-- <div>
                <x-mary-select wire:model.live="statusFilter">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </x-mary-select>
            </div> --}}

            {{-- Per Page --}}
            {{-- <div>
                <x-mary-select wire:model.live="perPage">
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                </x-mary-select>
            </div> --}}
        </div>
    </div>

    {{-- Categories Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Berita
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Urutan
                        </th>
                        <th
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if ($category->icon)
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3"
                                            style="background-color: {{ $category->color }}20; color: {{ $category->color }}">
                                            <x-mary-icon name="{{ $category->icon }}" class="w-4 h-4" />
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3"
                                            style="background-color: {{ $category->color }}">
                                            <span class="text-white text-sm font-bold">
                                                {{ substr($category->name, 0, 1) }}
                                            </span>
                                        </div>
                                    @endif

                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $category->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $category->slug }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ Str::limit($category->description, 50) }}
                                </div>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $category->news_count }} berita
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $category->id }})"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 {{ $category->is_active ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="sr-only">Toggle status</span>
                                    <span
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $category->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $category->sort_order }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <x-mary-button wire:navigate
                                        href="{{ route('admin.news-categories.edit', $category) }}" icon="o-pencil"
                                        class="btn-sm btn-ghost" tooltip="Edit" />

                                    <x-mary-button wire:click="confirmDelete({{ $category->id }})" icon="o-trash"
                                        class="btn-sm btn-ghost text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        tooltip="Hapus" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <x-mary-icon name="o-folder" class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                                    <p class="text-lg font-medium">Belum ada kategori</p>
                                    <p class="mt-1">Tambah kategori pertama untuk mengorganisir berita</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus">
        @if ($categoryToDelete)
            <div class="space-y-4">
                <p class="text-gray-600 dark:text-gray-400">
                    Apakah Anda yakin ingin menghapus kategori <strong>{{ $categoryToDelete->name }}</strong>?
                </p>

                @if ($categoryToDelete->news_count > 0)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <x-mary-icon name="o-exclamation-triangle" class="w-5 h-5 text-red-600 mr-2" />
                            <span class="text-red-800 dark:text-red-200 font-medium">
                                Kategori ini memiliki {{ $categoryToDelete->news_count }} berita dan tidak dapat
                                dihapus.
                            </span>
                        </div>
                    </div>
                @else
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <x-mary-icon name="o-exclamation-triangle" class="w-5 h-5 text-yellow-600 mr-2" />
                            <span class="text-yellow-800 dark:text-yellow-200">
                                Tindakan ini tidak dapat dibatalkan.
                            </span>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <x-slot:actions>
            <x-mary-button wire:click="cancelDelete" class="btn-ghost">
                Batal
            </x-mary-button>

            @if ($categoryToDelete && $categoryToDelete->news_count === 0)
                <x-mary-button wire:click="deleteCategory" class="btn-error" spinner="deleteCategory">
                    Hapus Kategori
                </x-mary-button>
            @endif
        </x-slot:actions>
    </x-mary-modal>
</div>
