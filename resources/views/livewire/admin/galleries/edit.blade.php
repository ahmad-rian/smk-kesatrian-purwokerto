<div>
    {{-- Header Section --}}
    <x-mary-header title="Edit Gallery: {{ $gallery->judul }}" subtitle="Kelola informasi dan gambar dalam gallery"
        progress-indicator>
        <x-slot:actions>
            <x-mary-button icon="o-arrow-left" link="{{ route('admin.galleries.index') }}" class="btn-ghost">
                Kembali
            </x-mary-button>
        </x-slot:actions>
    </x-mary-header>

    {{-- Tab Navigation --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button wire:click="switchTab('info')"
                    class="{{ $activeTab === 'info' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    style="font-family: 'Inter', sans-serif;">
                    <x-mary-icon name="o-information-circle" class="w-5 h-5 inline mr-2" />
                    Informasi Gallery
                </button>
                <button wire:click="switchTab('images')"
                    class="{{ $activeTab === 'images' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    style="font-family: 'Inter', sans-serif;">
                    <x-mary-icon name="o-photo" class="w-5 h-5 inline mr-2" />
                    Kelola Gambar ({{ $galleryImages->count() }})
                </button>
            </nav>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        {{-- Tab: Informasi Gallery --}}
        @if ($activeTab === 'info')
            <form wire:submit="update">
                <div class="p-6 space-y-6">
                    {{-- Informasi Dasar --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Informasi Dasar
                        </h3>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {{-- Judul --}}
                            <div class="lg:col-span-2">
                                <x-mary-input wire:model.live="judul" label="Judul Gallery"
                                    placeholder="Masukkan judul gallery..." required class="w-full" :error="$errors->first('judul')">
                                    <x-slot:append>
                                        @if ($autoGenerateSlug)
                                            <x-mary-icon name="o-link" class="w-5 h-5 text-green-500"
                                                tooltip="Auto-generate slug aktif" />
                                        @endif
                                    </x-slot:append>
                                </x-mary-input>
                            </div>

                            {{-- Slug --}}
                            <div class="lg:col-span-2">
                                <div class="flex items-end space-x-2">
                                    <div class="flex-1">
                                        <x-mary-input wire:model.live="slug" label="Slug URL"
                                            placeholder="url-friendly-slug" required class="w-full" :error="$errors->first('slug')"
                                            hint="URL yang akan digunakan untuk mengakses gallery ini" />
                                    </div>
                                    <x-mary-button icon="o-arrow-path" wire:click="enableAutoSlug"
                                        class="btn-ghost btn-sm mb-1" tooltip="Generate ulang slug dari judul" />
                                </div>
                                @if ($slug)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"
                                        style="font-family: 'Inter', sans-serif;">
                                        Preview URL: <span class="font-mono">{{ url('/gallery/' . $slug) }}</span>
                                    </p>
                                @endif
                            </div>

                            {{-- Deskripsi --}}
                            <div class="lg:col-span-2">
                                <x-mary-textarea wire:model.live="deskripsi" label="Deskripsi"
                                    placeholder="Masukkan deskripsi gallery (opsional)..." rows="4" class="w-full"
                                    :error="$errors->first('deskripsi')" hint="Deskripsi singkat tentang gallery ini" />
                            </div>

                            {{-- Urutan --}}
                            <div>
                                <x-mary-input wire:model.live="urutan" label="Urutan Tampil" type="number"
                                    min="1" required class="w-full" :error="$errors->first('urutan')"
                                    hint="Urutan tampil gallery (angka kecil = tampil lebih dulu)" />
                            </div>

                            {{-- Status Aktif --}}
                            <div class="flex items-center space-x-3">
                                <x-mary-toggle wire:model.live="aktif" label="Status Aktif" :checked="$aktif"
                                    class="toggle-success" />
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"
                                        style="font-family: 'Inter', sans-serif;">
                                        {{ $aktif ? 'Gallery akan ditampilkan di website' : 'Gallery tidak akan ditampilkan di website' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <hr class="border-gray-200 dark:border-gray-700">

                    {{-- Gambar Sampul --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Gambar Sampul
                        </h3>

                        <div class="space-y-4">
                            {{-- Gambar Sampul Saat Ini --}}
                            @if ($currentGambarSampul)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        style="font-family: 'Inter', sans-serif;">
                                        Gambar Sampul Saat Ini
                                    </label>
                                    <div class="relative inline-block">
                                        <img src="{{ $this->currentGambarSampulUrl }}" alt="{{ $gallery->judul }}"
                                            class="h-32 w-auto rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm"
                                            onerror="this.onerror=null; this.src='/images/placeholder-image.svg'; console.error('Gambar tidak dapat dimuat:', this.alt);">
                                        <x-mary-button icon="o-trash" wire:click="removeCurrentGambarSampul"
                                            class="btn-sm btn-circle btn-error absolute -top-2 -right-2"
                                            tooltip="Hapus gambar sampul" />
                                    </div>
                                </div>
                            @endif

                            {{-- Upload Gambar Baru --}}
                            <div>
                                <x-mary-file wire:model="gambar_sampul"
                                    label="{{ $currentGambarSampul ? 'Ganti Gambar Sampul' : 'Upload Gambar Sampul' }}"
                                    accept="image/*" class="w-full" :error="$errors->first('gambar_sampul')"
                                    hint="Format yang didukung: JPG, PNG, WebP. Maksimal 2MB. Gambar akan dikonversi ke WebP secara otomatis.">
                                    <x-slot:append>
                                        @if ($gambar_sampul)
                                            <x-mary-button icon="o-x-mark" wire:click="removeImage"
                                                class="btn-ghost btn-sm text-red-500" tooltip="Hapus gambar" />
                                        @endif
                                    </x-slot:append>
                                </x-mary-file>
                            </div>

                            {{-- Preview Gambar Baru --}}
                            @if ($gambar_sampul)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2"
                                        style="font-family: 'Inter', sans-serif;">
                                        Preview Gambar Baru
                                    </label>
                                    <div class="relative inline-block">
                                        <img src="{{ $this->imagePreview }}" alt="Preview"
                                            class="h-32 w-auto rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm"
                                            onerror="this.onerror=null; this.src='/images/placeholder-image.svg'; console.error('Gambar tidak dapat dimuat:', this.alt);">
                                        <x-mary-button icon="o-x-mark" wire:click="removeImage"
                                            class="btn-sm btn-circle btn-error absolute -top-2 -right-2"
                                            tooltip="Hapus gambar" />
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div
                    class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                    <div class="flex flex-col sm:flex-row sm:justify-end sm:items-center gap-3">
                        <x-mary-button label="Batal" wire:click="back" class="btn-ghost" />

                        <x-mary-button label="Simpan Perubahan" type="submit" icon="o-check" class="btn-primary"
                            spinner="update" :disabled="$isLoading" />
                    </div>
                </div>
            </form>
        @endif

        {{-- Tab: Kelola Gambar --}}
        @if ($activeTab === 'images')
            <div class="p-6 space-y-6">
                {{-- Upload Gambar Baru --}}
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        Upload Gambar Baru
                    </h3>

                    <div class="space-y-4">
                        <x-mary-file wire:model="new_images" label="Pilih Gambar"
                            accept="image/jpeg,image/png,image/gif,image/webp" multiple class="w-full"
                            :error="$errors->first('new_images')"
                            hint="Pilih satu atau beberapa gambar. Format yang didukung: JPG, PNG, GIF, WebP. Maksimal 2MB per file." />

                        {{-- Preview Gambar yang Akan Diupload --}}
                        @if ($new_images)
                            <div class="mt-4">
                                <h4 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-3"
                                    style="font-family: 'Inter', sans-serif;">
                                    Preview Gambar ({{ count($this->newImagesPreview) }})
                                </h4>

                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                                    @foreach ($this->newImagesPreview as $index => $preview)
                                        <div class="relative group">
                                            <div
                                                class="aspect-square overflow-hidden rounded-lg border border-gray-200 dark:border-gray-600">
                                                <img src="{{ $preview['url'] }}" alt="Preview {{ $index + 1 }}"
                                                    class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                                                    onerror="this.onerror=null; this.src='/images/placeholder-image.svg'; console.error('Gambar tidak dapat dimuat:', this.alt);">
                                            </div>
                                            <div class="mt-1 text-xs text-gray-500 truncate">
                                                {{ $preview['name'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex justify-end mt-4">
                                <x-mary-button label="Upload Gambar" wire:click="uploadImages"
                                    icon="o-cloud-arrow-up" class="btn-primary" spinner="uploadImages" />
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Divider --}}
                <hr class="border-gray-200 dark:border-gray-700">

                {{-- Daftar Gambar  --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            Gambar dalam Gallery ({{ $galleryImages->count() }})
                        </h3>
                    </div>

                    @if ($galleryImages->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                            @foreach ($galleryImages as $image)
                                <div class="relative group bg-white rounded-lg">
                                    {{-- Gambar --}}
                                    <div
                                        class="aspect-square overflow-hidden rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-100">
                                        <img src="{{ Storage::url($image->gambar) }}"
                                            alt="Gallery Image {{ $image->urutan }}"
                                            class="w-full h-full object-cover" loading="lazy"
                                            onerror="this.onerror=null; this.style.display='none'; this.parentNode.innerHTML='<div class=\\'w-full h-full flex items-center justify-center bg-gray-200 text-gray-400\\'>Gambar tidak dapat dimuat</div>';">
                                    </div>

                                    {{-- Urutan Badge --}}
                                    <div class="absolute top-2 left-2 z-10">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white bg-opacity-90 text-gray-800 shadow-sm"
                                            style="font-family: 'Inter', sans-serif;">
                                            #{{ $image->urutan }}
                                        </span>
                                    </div>

                                    {{-- Action Buttons - Always Visible on Mobile, Hover on Desktop --}}
                                    <div
                                        class="absolute top-2 right-2 z-10 flex space-x-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-200">
                                        {{-- View Button --}}
                                        <button onclick="window.open('{{ Storage::url($image->gambar) }}', '_blank')"
                                            class="p-2 bg-white bg-opacity-90 hover:bg-opacity-100 rounded-full shadow-sm hover:shadow-md transition-all duration-200"
                                            title="Lihat gambar">
                                            <x-mary-icon name="o-eye" class="w-4 h-4 text-gray-600" />
                                        </button>

                                        {{-- Delete Button --}}
                                        <button wire:click="confirmDeleteImage('{{ $image->id }}')"
                                            class="p-2 bg-red-500 bg-opacity-90 hover:bg-opacity-100 text-white rounded-full shadow-sm hover:shadow-md transition-all duration-200"
                                            title="Hapus gambar">
                                            <x-mary-icon name="o-trash" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div
                            class="text-center py-12 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                            <x-mary-icon name="o-photo" class="mx-auto h-12 w-12 text-gray-400" />
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white"
                                style="font-family: 'Bricolage Grotesque', sans-serif;">
                                Belum ada gambar
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                                style="font-family: 'Inter', sans-serif;">
                                Upload gambar pertama untuk gallery ini.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Delete Image Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteImageModal" title="Konfirmasi Hapus Gambar" class="backdrop-blur">
        <div class="py-4">
            <div class="flex items-center space-x-3 mb-4">
                <div class="flex-shrink-0">
                    <x-mary-icon name="o-exclamation-triangle" class="h-8 w-8 text-red-600" />
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        Hapus Gambar
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"
                        style="font-family: 'Inter', sans-serif;">
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <p class="text-sm text-red-800 dark:text-red-200" style="font-family: 'Inter', sans-serif;">
                    Apakah Anda yakin ingin menghapus gambar ini dari gallery?
                    <br><br>
                    <span class="font-medium">Peringatan:</span> File gambar akan dihapus secara permanen dari server.
                </p>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="cancelDeleteImage" class="btn-ghost" />
            <x-mary-button label="Hapus" wire:click="deleteImage" class="btn-error" spinner="deleteImage" />
        </x-slot:actions>
    </x-mary-modal>

    {{-- Loading Overlay - Hanya untuk operasi yang membutuhkan waktu lama --}}
    <div wire:loading.flex wire:target="update, deleteImage"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <x-mary-loading class="loading-spinner loading-md" />
                <span class="text-gray-700 dark:text-gray-300" style="font-family: 'Inter', sans-serif;">
                    Menyimpan perubahan...
                </span>
            </div>
        </div>
    </div>
</div>
