<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-base-content mb-2" style="font-family: 'Bricolage Grotesque', sans-serif;">
                Tambah Berita
            </h1>
            <p class="text-base-content/70" style="font-family: 'Inter', sans-serif;">
                Buat berita baru untuk dipublikasikan
            </p>
        </div>
        <button wire:click="cancel" class="btn btn-ghost" wire:navigate>
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali
        </button>
    </div>

    <!-- Form Section -->
    <div class="bg-base-100 rounded-lg shadow-sm border border-base-300 p-6">
        <form wire:submit="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Judul -->
                    <div>
                        <label class="label">
                            <span class="label-text font-medium">Judul Berita <span class="text-error">*</span></span>
                        </label>
                        <input type="text" wire:model.live="judul" placeholder="Masukkan judul berita..."
                            class="input input-bordered w-full @error('judul') input-error @enderror">
                        @error('judul')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label class="label">
                            <span class="label-text font-medium">Slug URL <span class="text-error">*</span></span>
                        </label>
                        <input type="text" wire:model="slug" placeholder="slug-url-berita"
                            class="input input-bordered w-full @error('slug') input-error @enderror">
                        @error('slug')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="label">
                            <span class="label-text-alt text-base-content/60">
                                URL: {{ url('/berita') }}/{{ $slug ?: 'slug-berita' }}
                            </span>
                        </div>
                    </div>

                    <!-- Kategori & Penulis Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Kategori -->
                        <div>
                            <label class="label">
                                <span class="label-text font-medium">Kategori</span>
                            </label>
                            <select wire:model="news_category_id"
                                class="select select-bordered w-full @error('news_category_id') select-error @enderror">
                                <option value="">Pilih Kategori</option>
                                @foreach ($this->categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('news_category_id')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>

                        <!-- Penulis -->
                        <div>
                            <label class="label">
                                <span class="label-text font-medium">Penulis <span class="text-error">*</span></span>
                            </label>
                            <input type="text" wire:model="penulis" placeholder="Nama penulis"
                                class="input input-bordered w-full @error('penulis') input-error @enderror">
                            @error('penulis')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Ringkasan -->
                    <div>
                        <label class="label">
                            <span class="label-text font-medium">Ringkasan</span>
                        </label>
                        <textarea wire:model="ringkasan" rows="3" placeholder="Ringkasan singkat berita untuk preview..."
                            class="textarea textarea-bordered w-full @error('ringkasan') textarea-error @enderror"></textarea>
                        @error('ringkasan')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="label">
                            <span class="label-text-alt text-base-content/60">
                                Maksimal 500 karakter. Akan ditampilkan sebagai preview di listing berita.
                            </span>
                        </div>
                    </div>

                    <!-- Konten -->
                    <div>
                        <label class="label">
                            <span class="label-text font-medium">Konten Berita <span class="text-error">*</span></span>
                        </label>
                        <textarea wire:model="konten" rows="12" placeholder="Tulis konten berita di sini..."
                            class="textarea textarea-bordered w-full @error('konten') textarea-error @enderror"></textarea>
                        @error('konten')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                        <div class="label">
                            <span class="label-text-alt text-base-content/60">
                                Gunakan format teks biasa. HTML akan di-escape untuk keamanan.
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status -->
                    <div class="bg-base-200 rounded-lg p-4">
                        <h3 class="font-semibold text-base-content mb-4">Pengaturan Publikasi</h3>

                        <div>
                            <label class="label">
                                <span class="label-text font-medium">Status <span class="text-error">*</span></span>
                            </label>
                            <select wire:model="status"
                                class="select select-bordered w-full @error('status') select-error @enderror">
                                <option value="aktif">Aktif (Dipublikasikan)</option>
                                <option value="nonaktif">Nonaktif (Draft)</option>
                            </select>
                            @error('status')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="bg-base-200 rounded-lg p-4">
                        <h3 class="font-semibold text-base-content mb-4">Pengaturan SEO</h3>

                        <!-- Meta Title -->
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Meta Title</span>
                            </label>
                            <input type="text" wire:model="meta_title" placeholder="Judul untuk SEO (opsional)"
                                class="input input-bordered w-full @error('meta_title') input-error @enderror">
                            @error('meta_title')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror
                            <div class="label">
                                <span class="label-text-alt text-base-content/60">
                                    Kosongkan untuk menggunakan judul berita
                                </span>
                            </div>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-4">
                            <label class="label">
                                <span class="label-text font-medium">Meta Description</span>
                            </label>
                            <textarea wire:model="meta_description" rows="3" placeholder="Deskripsi untuk mesin pencari (opsional)"
                                class="textarea textarea-bordered w-full @error('meta_description') textarea-error @enderror"></textarea>
                            @error('meta_description')
                                <div class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </div>
                            @enderror
                            <div class="label">
                                <span class="label-text-alt text-base-content/60">
                                    Kosongkan untuk menggunakan ringkasan
                                </span>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div>
                            <label class="label">
                                <span class="label-text font-medium">Tags</span>
                            </label>
                            <input type="text" placeholder="Tag1, Tag2, Tag3..."
                                class="input input-bordered w-full" x-data="{ value: @entangle('tags').defer }"
                                x-on:input="value = $event.target.value.split(',').map(tag => tag.trim()).filter(tag => tag)">
                            <div class="label">
                                <span class="label-text-alt text-base-content/60">
                                    Pisahkan dengan koma
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Gambar -->
                    <div class="bg-base-200 rounded-lg p-4">
                        <h3 class="font-semibold text-base-content mb-4">Gambar Berita</h3>

                        @if ($gambarPreview)
                            <!-- Preview Gambar -->
                            <div class="mb-4">
                                <img src="{{ $gambarPreview }}" alt="Preview"
                                    class="w-full h-48 object-cover rounded-lg border border-base-300">
                                <button type="button" wire:click="removeGambar"
                                    class="btn btn-sm btn-error mt-2 w-full">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus Gambar
                                </button>
                            </div>
                        @else
                            <!-- Upload Area -->
                            <div
                                class="border-2 border-dashed border-base-300 rounded-lg p-6 text-center hover:border-primary transition-colors">
                                <input type="file" wire:model="gambar" accept="image/*" class="hidden"
                                    id="gambar-upload">
                                <label for="gambar-upload" class="cursor-pointer">
                                    <svg class="w-12 h-12 mx-auto text-base-content/40 mb-4" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="text-base-content/60 mb-2">Klik untuk upload gambar</p>
                                    <p class="text-sm text-base-content/40">PNG, JPG hingga 2MB</p>
                                </label>
                            </div>
                        @endif

                        @error('gambar')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror

                        <div class="text-xs text-base-content/60 mt-2">
                            <p>• Ukuran maksimal: 2MB</p>
                            <p>• Dimensi minimal: 300x200 pixel</p>
                            <p>• Format: JPG, PNG</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button type="submit" class="btn btn-primary w-full" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Berita
                            </span>
                            <span wire:loading>
                                <span class="loading loading-spinner loading-sm mr-2"></span>
                                Menyimpan...
                            </span>
                        </button>

                        <button type="button" wire:click="cancel" class="btn btn-ghost w-full">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
