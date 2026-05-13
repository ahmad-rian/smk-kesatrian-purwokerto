<div class="space-y-6">
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                Tambah Menu Frontend
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                Tambah menu baru untuk navigasi halaman depan
            </p>
        </div>
        <div class="flex gap-2">
            <x-mary-button wire:click="cancel" icon="o-arrow-left" class="btn-ghost">
                Kembali
            </x-mary-button>
        </div>
    </div>

    {{-- Form Section --}}
    <form wire:submit="save" class="space-y-6">
        {{-- Card: Informasi Menu --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                Informasi Menu
            </h2>

            {{-- Title --}}
            <div>
                <x-mary-input wire:model.live.debounce.300ms="title" label="Nama Menu *"
                    placeholder="Contoh: Beranda, Tentang Kami, Gallery, dll"
                    hint="Label yang ditampilkan di navbar" />
            </div>

            {{-- Link Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Tipe Tujuan *
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                        {{ $type === 'route' ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="type" value="route" class="radio radio-primary radio-sm">
                        <div>
                            <div class="text-sm font-medium">Halaman Internal</div>
                            <div class="text-xs text-gray-500">Pilih dari halaman yang sudah ada</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                        {{ $type === 'url' ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="type" value="url" class="radio radio-primary radio-sm">
                        <div>
                            <div class="text-sm font-medium">Custom URL</div>
                            <div class="text-xs text-gray-500">Link ke URL eksternal/custom</div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border-2 rounded-lg cursor-pointer transition-all
                        {{ $type === 'page' ? 'border-primary bg-primary/5' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300' }}">
                        <input type="radio" wire:model.live="type" value="page" class="radio radio-primary radio-sm">
                        <div>
                            <div class="text-sm font-medium">Custom Page</div>
                            <div class="text-xs text-gray-500">Buat halaman baru dengan konten</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Route Name --}}
            @if($type === 'route')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Pilih Halaman *
                    </label>
                    <select wire:model="route_name" class="select select-bordered w-full">
                        <option value="">-- Pilih Halaman --</option>
                        @foreach($this->availableRoutes as $routeValue => $routeLabel)
                            <option value="{{ $routeValue }}">{{ $routeLabel }}</option>
                        @endforeach
                    </select>
                    @error('route_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            {{-- Custom URL --}}
            @if($type === 'url')
                <div>
                    <x-mary-input wire:model="url" label="Custom URL *"
                        placeholder="https://example.com atau /halaman-custom"
                        hint="URL lengkap atau path relatif" />
                </div>
            @endif
        </div>

        {{-- Card: Custom Page Content (only when type = page) --}}
        @if($type === 'page')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                    <x-mary-icon name="o-document-text" class="w-5 h-5 inline mr-1" />
                    Konten Halaman
                </h2>

                {{-- Slug --}}
                <div>
                    <x-mary-input wire:model="page_slug" label="Slug URL *"
                        placeholder="contoh-halaman-baru"
                        prefix="/page/"
                        hint="URL halaman: /page/{{ $page_slug ?: 'slug-halaman' }}" />
                </div>

                {{-- Excerpt --}}
                <div>
                    <x-mary-textarea wire:model="page_excerpt" label="Ringkasan / Subtitle"
                        placeholder="Deskripsi singkat yang tampil di bawah judul hero..."
                        rows="2"
                        hint="Tampil di hero section sebagai subtitle. Maksimal 500 karakter." />
                </div>

                {{-- Content --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Konten Halaman *
                    </label>
                    <textarea wire:model="page_content" rows="15"
                        placeholder="Tulis konten halaman di sini... Mendukung HTML untuk formatting."
                        class="textarea textarea-bordered w-full font-mono text-sm @error('page_content') textarea-error @enderror"></textarea>
                    @error('page_content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">
                        Mendukung HTML. Gunakan tag seperti &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;img&gt;, &lt;table&gt; untuk formatting.
                    </p>
                </div>

                {{-- Meta Description --}}
                <div>
                    <x-mary-input wire:model="page_meta_description" label="Meta Description (SEO)"
                        placeholder="Deskripsi untuk mesin pencari..."
                        hint="Opsional. Muncul di hasil pencarian Google. Maks 255 karakter." />
                </div>
            </div>

            {{-- Card: Hero & Tampilan --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                    <x-mary-icon name="o-paint-brush" class="w-5 h-5 inline mr-1" />
                    Tampilan Halaman
                </h2>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Show Hero Toggle --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Hero Section</label>
                        <div class="flex items-center">
                            <input wire:model.live="page_show_hero" type="checkbox" id="page_show_hero"
                                class="checkbox checkbox-primary checkbox-sm">
                            <label for="page_show_hero" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Tampilkan hero section di atas halaman
                            </label>
                        </div>
                    </div>

                    {{-- Hero Style --}}
                    @if($page_show_hero)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gaya Hero</label>
                            <select wire:model="page_hero_style" class="select select-bordered w-full">
                                <option value="gradient">Gradient (Biru-Indigo gelap)</option>
                                <option value="simple">Simple (Background terang)</option>
                                <option value="image">Image (Gambar sebagai background)</option>
                            </select>
                        </div>
                    @endif
                </div>

                {{-- Featured Image --}}
                <div>
                    <x-mary-file wire:model="page_featured_image" label="Gambar Utama"
                        accept="image/*"
                        hint="Digunakan sebagai background hero (jika gaya Image) atau ilustrasi. Maks 2MB." />

                    @if($page_featured_image)
                        <div class="mt-3">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Preview:</p>
                            <img src="{{ $page_featured_image->temporaryUrl() }}"
                                alt="Preview"
                                class="w-full max-w-md h-48 object-cover rounded-lg shadow">
                        </div>
                    @endif
                </div>

                {{-- Published --}}
                <div>
                    <div class="flex items-center">
                        <input wire:model="page_is_published" type="checkbox" id="page_is_published"
                            class="checkbox checkbox-primary checkbox-sm">
                        <label for="page_is_published" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Halaman dipublikasikan
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Halaman yang tidak dipublikasikan akan menampilkan 404</p>
                </div>
            </div>
        @endif

        {{-- Card: Pengaturan Menu --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                Pengaturan Menu
            </h2>

            {{-- Icon Selection --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Ikon Menu <span class="font-normal text-gray-400">(untuk mobile menu)</span>
                </label>
                <div class="grid grid-cols-8 sm:grid-cols-10 lg:grid-cols-15 gap-2 max-h-40 overflow-y-auto p-2 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div wire:click="$set('icon', '')"
                        class="w-10 h-10 border-2 rounded-lg flex items-center justify-center cursor-pointer transition-all duration-200 {{ $icon === '' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300' }}"
                        title="Tanpa ikon">
                        <span class="text-xs text-gray-400">-</span>
                    </div>

                    @foreach ($availableIcons as $iconValue => $iconName)
                        <div wire:click="$set('icon', '{{ $iconValue }}')"
                            class="w-10 h-10 border-2 rounded-lg flex items-center justify-center cursor-pointer transition-all duration-200 {{ $icon === $iconValue ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600 hover:border-gray-300' }}"
                            title="{{ $iconName }}">
                            <x-mary-icon name="{{ $iconValue }}" class="w-5 h-5 text-gray-600 dark:text-gray-300" />
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Parent Menu & Sort Order --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Parent Menu
                    </label>
                    <select wire:model="parent_id" class="select select-bordered w-full">
                        <option value="">-- Menu Utama (Top Level) --</option>
                        @foreach($this->parentMenus as $parentMenu)
                            <option value="{{ $parentMenu->id }}">{{ $parentMenu->title }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Kosongkan untuk menu utama, pilih parent untuk submenu</p>
                </div>

                <div>
                    <x-mary-input wire:model="sort_order" label="Urutan" type="number" min="0"
                        hint="Semakin kecil angka, semakin kiri posisi menu" />
                </div>
            </div>

            {{-- Toggle Options --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Status</label>
                    <div class="flex items-center">
                        <input wire:model="is_active" type="checkbox" id="is_active" class="checkbox checkbox-primary checkbox-sm">
                        <label for="is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Menu aktif</label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Menu nonaktif tidak tampil di frontend</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tab Baru</label>
                    <div class="flex items-center">
                        <input wire:model="open_in_new_tab" type="checkbox" id="open_in_new_tab" class="checkbox checkbox-primary checkbox-sm">
                        <label for="open_in_new_tab" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Buka di tab baru</label>
                    </div>
                </div>

                <div>
                    <x-mary-input wire:model="css_class" label="Custom CSS Class"
                        placeholder="contoh: text-red-500"
                        hint="Opsional, untuk styling khusus" />
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex justify-end gap-3">
            <x-mary-button type="button" wire:click="cancel" class="btn-ghost">
                Batal
            </x-mary-button>
            <x-mary-button type="submit" class="btn-primary" spinner="save">
                {{ $type === 'page' ? 'Simpan Menu & Halaman' : 'Simpan Menu' }}
            </x-mary-button>
        </div>
    </form>
</div>
