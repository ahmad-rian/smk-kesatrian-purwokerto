<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Edit Kategori: {{ $studyProgramCategory->name }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Perbarui informasi kategori program studi
            </p>
        </div>

        <div class="flex gap-2">
            <x-mary-button wire:navigate href="{{ route('admin.study-program-categories.index') }}" icon="o-arrow-left" class="btn-ghost">
                Kembali
            </x-mary-button>
        </div>
    </div>

    {{-- Category Stats --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $studyProgramCategory->study_programs_count ?? 0 }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Total Program Studi
                </div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ $studyProgramCategory->active_study_programs_count ?? 0 }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Program Studi Aktif
                </div>
            </div>
            <div class="text-center">
                <div
                    class="text-2xl font-bold {{ $studyProgramCategory->is_active ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $studyProgramCategory->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Status Kategori
                </div>
            </div>
        </div>
    </div>

    {{-- Form Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form wire:submit="save" class="p-6 space-y-6">
            {{-- Basic Information --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Name --}}
                <div>
                    <x-mary-input wire:model.live.debounce.300ms="name" label="Nama Kategori *"
                        placeholder="Masukkan nama kategori" hint="Nama akan otomatis membuat slug URL" />
                </div>

                {{-- Slug --}}
                <div>
                    <div class="flex items-end gap-2">
                        <div class="flex-1">
                            <x-mary-input wire:model="slug" label="Slug URL *" placeholder="slug-url-kategori"
                                hint="URL-friendly identifier untuk kategori" />
                        </div>
                        <x-mary-button type="button" wire:click="generateSlug" icon="o-arrow-path"
                            class="btn-sm btn-ghost mb-6" tooltip="Generate slug dari nama" />
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div>
                <x-mary-textarea wire:model="description" label="Deskripsi"
                    placeholder="Deskripsi singkat tentang kategori ini..." rows="3"
                    hint="Maksimal 500 karakter" />
            </div>

            {{-- Visual Settings --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Color Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Warna Kategori *
                    </label>
                    <div class="grid grid-cols-5 gap-3">
                        @foreach ($availableColors as $colorValue => $colorName)
                            <div wire:click="$set('color', '{{ $colorValue }}')"
                                class="relative cursor-pointer group">
                                <div class="w-12 h-12 rounded-lg border-2 transition-all duration-200 {{ $color === $colorValue ? 'border-gray-400 ring-2 ring-offset-2 ring-gray-300' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300' }}"
                                    style="background-color: {{ $colorValue }}"></div>
                                @if ($color === $colorValue)
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <x-mary-icon name="o-check" class="w-6 h-6 text-white drop-shadow" />
                                    </div>
                                @endif
                                <div
                                    class="absolute -bottom-6 left-1/2 transform -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span
                                        class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $colorName }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Icon Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Ikon Kategori
                    </label>
                    <div class="grid grid-cols-6 gap-2 max-h-32 overflow-y-auto">
                        {{-- No Icon Option --}}
                        <div wire:click="$set('icon', '')"
                            class="w-10 h-10 border-2 rounded-lg flex items-center justify-center cursor-pointer transition-all duration-200 {{ $icon === '' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300' }}"
                            title="Tanpa ikon">
                            <span class="text-xs text-gray-400">No</span>
                        </div>

                        @foreach ($availableIcons as $iconValue => $iconName)
                            <div wire:click="$set('icon', '{{ $iconValue }}')"
                                class="w-10 h-10 border-2 rounded-lg flex items-center justify-center cursor-pointer transition-all duration-200 {{ $icon === $iconValue ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300' }}"
                                title="{{ $iconName }}">
                                <x-mary-icon name="{{ $iconValue }}"
                                    class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Settings --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Sort Order --}}
                <div>
                    <x-mary-input wire:model="sort_order" label="Urutan" type="number" min="0"
                        hint="Urutan tampilan kategori (semakin kecil semakin atas)" />
                </div>

                {{-- Active Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                        Status
                    </label>
                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox" id="is_active"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Kategori aktif
                        </label>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Kategori yang tidak aktif tidak akan tampil di frontend
                    </p>
                </div>
            </div>

            {{-- Preview --}}
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Preview
                </label>
                <div class="flex items-center">
                    @if ($icon)
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3"
                            style="background-color: {{ $color }}20; color: {{ $color }}">
                            <x-mary-icon name="{{ $icon }}" class="w-5 h-5" />
                        </div>
                    @else
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3"
                            style="background-color: {{ $color }}">
                            <span class="text-white text-sm font-bold">
                                {{ $name ? substr($name, 0, 1) : 'K' }}
                            </span>
                        </div>
                    @endif

                    <div>
                        <div class="text-lg font-medium text-gray-900 dark:text-white">
                            {{ $name ?: 'Nama Kategori' }}
                        </div>
                        @if ($description)
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $description }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
                {{-- Delete Button --}}
                <x-mary-button type="button" wire:click="confirmDelete" icon="o-trash"
                    class="btn-error btn-outline" spinner="confirmDelete">
                    Hapus Kategori
                </x-mary-button>

                <div class="flex gap-3">
                    <x-mary-button type="button" wire:navigate href="{{ route('admin.study-program-categories.index') }}" class="btn-ghost">
                        Batal
                    </x-mary-button>

                    <x-mary-button type="submit" class="btn-primary" spinner="save">
                        Perbarui Kategori
                    </x-mary-button>
                </div>
            </div>
        </form>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus">
        <div class="space-y-4">
            <p class="text-gray-600 dark:text-gray-400">
                Apakah Anda yakin ingin menghapus kategori <strong>{{ $studyProgramCategory->name }}</strong>?
            </p>

            @if (($studyProgramCategory->study_programs_count ?? 0) > 0)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <x-mary-icon name="o-exclamation-triangle" class="w-5 h-5 text-red-600 mr-2" />
                        <span class="text-red-800 dark:text-red-200 font-medium">
                            Kategori ini memiliki {{ $studyProgramCategory->study_programs_count }} program studi dan tidak dapat
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

        <x-slot:actions>
            <x-mary-button wire:click="cancelDelete" class="btn-ghost">
                Batal
            </x-mary-button>

            @if (($studyProgramCategory->study_programs_count ?? 0) === 0)
                <x-mary-button wire:click="deleteCategory" class="btn-error" spinner="deleteCategory">
                    Hapus Kategori
                </x-mary-button>
            @endif
        </x-slot:actions>
    </x-mary-modal>
</div>
