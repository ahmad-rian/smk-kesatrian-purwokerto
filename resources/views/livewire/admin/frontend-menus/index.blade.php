<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Menu Frontend
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Kelola menu navigasi di halaman depan website
            </p>
        </div>

        <div class="flex gap-2">
            <x-mary-button wire:navigate href="{{ route('admin.frontend-menus.create') }}" icon="o-plus"
                class="btn-primary">
                Tambah Menu
            </x-mary-button>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-mary-input wire:model.live.debounce.300ms="search" placeholder="Cari menu..."
                    icon="o-magnifying-glass" />
            </div>
            <div>
                <select wire:model.live="statusFilter"
                    class="select select-bordered w-full">
                    <option value="all">Semua Status</option>
                    <option value="active">Aktif</option>
                    <option value="inactive">Nonaktif</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Menu Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Urutan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Menu
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Tujuan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($menus as $menu)
                        {{-- Parent Menu Row --}}
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-1">
                                    <x-mary-button wire:click="moveUp({{ $menu->id }})" icon="o-chevron-up"
                                        class="btn-xs btn-ghost" tooltip="Naik" />
                                    <span class="text-sm text-gray-600 dark:text-gray-400 w-6 text-center">{{ $menu->sort_order }}</span>
                                    <x-mary-button wire:click="moveDown({{ $menu->id }})" icon="o-chevron-down"
                                        class="btn-xs btn-ghost" tooltip="Turun" />
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($menu->icon)
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center mr-3">
                                            <x-mary-icon name="{{ $menu->icon }}" class="w-4 h-4 text-primary" />
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $menu->title }}
                                        </div>
                                        @if($menu->activeChildren->count() > 0)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $menu->activeChildren->count() }} submenu
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    @if($menu->custom_page_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <x-mary-icon name="o-document-text" class="w-3 h-3 mr-1" />
                                            Custom Page
                                        </span>
                                    @elseif($menu->route_name)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            route: {{ $menu->route_name }}
                                        </span>
                                    @elseif($menu->url)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                            {{ Str::limit($menu->url, 30) }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                    @if($menu->open_in_new_tab)
                                        <x-mary-icon name="o-arrow-top-right-on-square" class="w-3 h-3 inline ml-1 text-gray-400" />
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="toggleStatus({{ $menu->id }})"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $menu->is_active ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $menu->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <x-mary-button wire:navigate
                                        href="{{ route('admin.frontend-menus.edit', $menu) }}" icon="o-pencil"
                                        class="btn-sm btn-ghost" tooltip="Edit" />
                                    <x-mary-button wire:click="confirmDelete({{ $menu->id }})" icon="o-trash"
                                        class="btn-sm btn-ghost text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20"
                                        tooltip="Hapus" />
                                </div>
                            </td>
                        </tr>

                        {{-- Children / Submenu Rows --}}
                        @foreach($menu->activeChildren as $child)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center gap-1 pl-6">
                                        <x-mary-button wire:click="moveUp({{ $child->id }})" icon="o-chevron-up"
                                            class="btn-xs btn-ghost" tooltip="Naik" />
                                        <span class="text-xs text-gray-500 w-6 text-center">{{ $child->sort_order }}</span>
                                        <x-mary-button wire:click="moveDown({{ $child->id }})" icon="o-chevron-down"
                                            class="btn-xs btn-ghost" tooltip="Turun" />
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="flex items-center pl-6">
                                        <div class="w-4 border-l-2 border-b-2 border-gray-300 dark:border-gray-600 h-4 mr-2"></div>
                                        @if($child->icon)
                                            <div class="w-6 h-6 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center mr-2">
                                                <x-mary-icon name="{{ $child->icon }}" class="w-3 h-3 text-gray-500" />
                                            </div>
                                        @endif
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $child->title }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">
                                        @if($child->custom_page_id)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                <x-mary-icon name="o-document-text" class="w-3 h-3 mr-1" />
                                                Custom Page
                                            </span>
                                        @elseif($child->route_name)
                                            <span class="text-xs">route: {{ $child->route_name }}</span>
                                        @elseif($child->url)
                                            <span class="text-xs">{{ Str::limit($child->url, 30) }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $child->id }})"
                                        class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out {{ $child->is_active ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                                        <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $child->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                                    </button>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-mary-button wire:navigate
                                            href="{{ route('admin.frontend-menus.edit', $child) }}" icon="o-pencil"
                                            class="btn-xs btn-ghost" tooltip="Edit" />
                                        <x-mary-button wire:click="confirmDelete({{ $child->id }})" icon="o-trash"
                                            class="btn-xs btn-ghost text-red-600" tooltip="Hapus" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="text-gray-500 dark:text-gray-400">
                                    <x-mary-icon name="o-bars-3" class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                                    <p class="text-lg font-medium">Belum ada menu</p>
                                    <p class="mt-1">Tambah menu pertama untuk navigasi frontend</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus">
        @if ($menuToDelete)
            <div class="space-y-4">
                <p class="text-gray-600 dark:text-gray-400">
                    Apakah Anda yakin ingin menghapus menu <strong>{{ $menuToDelete->title }}</strong>?
                </p>

                @if($menuToDelete->children()->count() > 0)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <x-mary-icon name="o-exclamation-triangle" class="w-5 h-5 text-yellow-600 mr-2" />
                            <span class="text-yellow-800 dark:text-yellow-200">
                                Menu ini memiliki {{ $menuToDelete->children()->count() }} submenu yang juga akan dihapus.
                            </span>
                        </div>
                    </div>
                @endif

                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-mary-icon name="o-exclamation-triangle" class="w-5 h-5 text-red-600 mr-2" />
                        <span class="text-red-800 dark:text-red-200">
                            Tindakan ini tidak dapat dibatalkan.
                        </span>
                    </div>
                </div>
            </div>
        @endif

        <x-slot:actions>
            <x-mary-button wire:click="cancelDelete" class="btn-ghost">
                Batal
            </x-mary-button>
            <x-mary-button wire:click="deleteMenu" class="btn-error" spinner="deleteMenu">
                Hapus Menu
            </x-mary-button>
        </x-slot:actions>
    </x-mary-modal>
</div>
