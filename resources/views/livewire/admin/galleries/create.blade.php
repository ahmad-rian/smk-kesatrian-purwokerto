<div>
    {{-- Header Section --}}
    <x-mary-header title="Tambah Gallery Baru" subtitle="Buat gallery foto baru untuk website sekolah" progress-indicator>
        <x-slot:actions>
            <x-mary-button icon="o-arrow-left" link="{{ route('admin.galleries.index') }}" class="btn-ghost">
                Kembali
            </x-mary-button>
        </x-slot:actions>
    </x-mary-header>

    {{-- Form Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm">
        <form wire:submit="save">
            <div class="p-6 space-y-6">
                {{-- Informasi Dasar --}}
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                        Informasi Dasar
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Judul --}}
                        <div class="lg:col-span-2">
                            <x-mary-input 
                                wire:model.live="judul" 
                                label="Judul Gallery" 
                                placeholder="Masukkan judul gallery..."
                                required
                                class="w-full"
                                :error="$errors->first('judul')"
                            >
                                <x-slot:append>
                                    @if($autoGenerateSlug)
                                        <x-mary-icon name="o-link" class="w-5 h-5 text-green-500" tooltip="Auto-generate slug aktif" />
                                    @endif
                                </x-slot:append>
                            </x-mary-input>
                        </div>
                        
                        {{-- Slug --}}
                        <div class="lg:col-span-2">
                            <div class="flex items-end space-x-2">
                                <div class="flex-1">
                                    <x-mary-input 
                                        wire:model.live="slug" 
                                        label="Slug URL" 
                                        placeholder="url-friendly-slug"
                                        required
                                        class="w-full"
                                        :error="$errors->first('slug')"
                                        hint="URL yang akan digunakan untuk mengakses gallery ini"
                                    />
                                </div>
                                <x-mary-button 
                                    icon="o-arrow-path" 
                                    wire:click="enableAutoSlug" 
                                    class="btn-ghost btn-sm mb-1"
                                    tooltip="Generate ulang slug dari judul"
                                />
                            </div>
                            @if($slug)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1" style="font-family: 'Inter', sans-serif;">
                                    Preview URL: <span class="font-mono">{{ url('/gallery/' . $slug) }}</span>
                                </p>
                            @endif
                        </div>
                        
                        {{-- Deskripsi --}}
                        <div class="lg:col-span-2">
                            <x-mary-textarea 
                                wire:model.live="deskripsi" 
                                label="Deskripsi" 
                                placeholder="Masukkan deskripsi gallery (opsional)..."
                                rows="4"
                                class="w-full"
                                :error="$errors->first('deskripsi')"
                                hint="Deskripsi singkat tentang gallery ini"
                            />
                        </div>
                        
                        {{-- Urutan --}}
                        <div>
                            <x-mary-input 
                                wire:model.live="urutan" 
                                label="Urutan Tampil" 
                                type="number" 
                                min="1"
                                required
                                class="w-full"
                                :error="$errors->first('urutan')"
                                hint="Urutan tampil gallery (angka kecil = tampil lebih dulu)"
                            />
                        </div>
                        
                        {{-- Status Aktif --}}
                        <div class="flex items-center space-x-3">
                            <x-mary-toggle 
                                wire:model.live="aktif" 
                                label="Status Aktif"
                                :checked="$aktif"
                                class="toggle-success"
                            />
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400" style="font-family: 'Inter', sans-serif;">
                                    {{ $aktif ? 'Gallery akan ditampilkan di website' : 'Gallery tidak akan ditampilkan di website' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Divider --}}
                <hr class="border-gray-200 dark:border-gray-700">
                
                {{-- Upload Gambar Sampul --}}
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4" style="font-family: 'Bricolage Grotesque', sans-serif;">
                        Gambar Sampul
                    </h3>
                    
                    <div class="space-y-4">
                        {{-- File Input --}}
                        <div>
                            <x-mary-file 
                                wire:model="gambar_sampul" 
                                label="Pilih Gambar Sampul" 
                                accept="image/*"
                                class="w-full"
                                :error="$errors->first('gambar_sampul')"
                                hint="Format yang didukung: JPG, PNG, WebP. Maksimal 2MB. Gambar akan dikonversi ke WebP secara otomatis."
                            >
                                <x-slot:append>
                                    @if($gambar_sampul)
                                        <x-mary-button 
                                            icon="o-x-mark" 
                                            wire:click="removeImage" 
                                            class="btn-ghost btn-sm text-red-500"
                                            tooltip="Hapus gambar"
                                        />
                                    @endif
                                </x-slot:append>
                            </x-mary-file>
                        </div>
                        
                        {{-- Preview Gambar --}}
                        @if($gambar_sampul)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" style="font-family: 'Inter', sans-serif;">
                                    Preview Gambar Sampul
                                </label>
                                <div class="relative inline-block">
                                    <img 
                                        src="{{ $this->imagePreview }}" 
                                        alt="Preview" 
                                        class="h-32 w-auto rounded-lg border border-gray-200 dark:border-gray-600 shadow-sm"
                                    >
                                    <x-mary-button 
                                        icon="o-x-mark" 
                                        wire:click="removeImage" 
                                        class="btn-sm btn-circle btn-error absolute -top-2 -right-2"
                                        tooltip="Hapus gambar"
                                    />
                                </div>
                            </div>
                        @else
                            {{-- Upload Area --}}
                            <div 
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-8 text-center hover:border-gray-400 dark:hover:border-gray-500 transition-colors cursor-pointer"
                                onclick="document.querySelector('input[type=file]').click()"
                            >
                                <x-mary-icon name="o-cloud-arrow-up" class="mx-auto h-12 w-12 text-gray-400" />
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400" style="font-family: 'Inter', sans-serif;">
                                    <span class="font-medium">Klik untuk upload</span> atau drag & drop
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500" style="font-family: 'Inter', sans-serif;">
                                    PNG, JPG, WebP hingga 2MB
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Form Actions --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 rounded-b-lg">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    {{-- Info --}}
                    <div class="text-sm text-gray-500 dark:text-gray-400" style="font-family: 'Inter', sans-serif;">
                        <x-mary-icon name="o-information-circle" class="inline w-4 h-4 mr-1" />
                        Setelah menyimpan, Anda dapat menambahkan gambar-gambar ke dalam gallery.
                    </div>
                    
                    {{-- Buttons --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        <x-mary-button 
                            label="Batal" 
                            wire:click="back" 
                            class="btn-ghost"
                        />
                        
                        <x-mary-button 
                            label="Simpan Gallery" 
                            type="submit" 
                            icon="o-check" 
                            class="btn-primary"
                            spinner="save"
                            :disabled="$isLoading"
                        />
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Loading Overlay --}}
    <div wire:loading.flex wire:target="save" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
            <div class="flex items-center space-x-3">
                <x-mary-loading class="loading-spinner loading-md" />
                <span class="text-gray-700 dark:text-gray-300" style="font-family: 'Inter', sans-serif;">
                    Menyimpan gallery...
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Alpine.js untuk interaksi tambahan --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('galleryCreate', () => ({
            // Auto-focus pada input judul saat halaman dimuat
            init() {
                this.$nextTick(() => {
                    const judulInput = this.$el.querySelector('input[wire\\:model\.live="judul"]');
                    if (judulInput) {
                        judulInput.focus();
                    }
                });
            },
            
            // Preview drag & drop
            handleDragOver(event) {
                event.preventDefault();
                event.currentTarget.classList.add('border-blue-400', 'bg-blue-50');
            },
            
            handleDragLeave(event) {
                event.preventDefault();
                event.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
            },
            
            handleDrop(event) {
                event.preventDefault();
                event.currentTarget.classList.remove('border-blue-400', 'bg-blue-50');
                
                const files = event.dataTransfer.files;
                if (files.length > 0) {
                    const fileInput = this.$el.querySelector('input[type="file"]');
                    if (fileInput) {
                        fileInput.files = files;
                        fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            }
        }));
    });
</script>