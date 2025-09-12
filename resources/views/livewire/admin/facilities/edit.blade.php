<div>
    {{-- Header Section --}}
    <x-mary-header title="Edit Fasilitas" separator>
        <x-slot:subtitle>
            Perbarui data fasilitas: {{ $facility->nama }}
        </x-slot:subtitle>
        <x-slot:actions>
            <x-mary-button label="Kembali" icon="o-arrow-left" link="{{ route('admin.facilities.index') }}" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Form Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <x-mary-form wire:submit="update">
                <x-mary-card title="Informasi Fasilitas" subtitle="Perbarui data fasilitas sekolah">
                    {{-- Nama Fasilitas --}}
                    <x-mary-input
                        label="Nama Fasilitas"
                        wire:model="nama"
                        placeholder="Contoh: Laboratorium Komputer"
                        hint="Nama unik untuk mengidentifikasi fasilitas"
                        required />

                    {{-- Program Studi --}}
                    <x-mary-select
                        label="Program Studi"
                        wire:model="study_program_id"
                        :options="$studyPrograms"
                        option-value="id"
                        option-label="nama"
                        placeholder="Pilih Program Studi"
                        hint="Fasilitas ini digunakan untuk program studi mana?"
                        required />

                    {{-- Kategori --}}
                    <x-mary-select
                        label="Kategori Fasilitas"
                        wire:model="kategori"
                        :options="$this->kategoriOptions"
                        option-value="value"
                        option-label="label"
                        placeholder="Pilih Kategori (Opsional)"
                        hint="Kategori membantu mengelompokkan fasilitas" />

                    {{-- Deskripsi --}}
                    <x-mary-textarea
                        label="Deskripsi Fasilitas"
                        wire:model="deskripsi"
                        placeholder="Jelaskan detail fasilitas, peralatan yang tersedia, kapasitas, dll."
                        hint="Minimal 10 karakter, maksimal 1000 karakter"
                        rows="5"
                        required />

                    {{-- Form Actions --}}
                    <x-slot:actions>
                        <x-mary-button label="Batal" wire:click="cancel" />
                        <x-mary-button label="Update Fasilitas" type="submit" icon="o-check" class="btn-primary" spinner="update" />
                    </x-slot:actions>
                </x-mary-card>
            </x-mary-form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Upload Gambar --}}
            <x-mary-card title="Gambar Fasilitas" subtitle="Ganti atau upload foto fasilitas">
                <div class="space-y-4">
                    {{-- File Input --}}
                    <x-mary-file
                        label="Pilih Gambar Baru"
                        wire:model="gambar"
                        accept="image/*"
                        hint="Format: JPG, PNG, GIF, WebP. Maksimal 2MB" />

                    {{-- Preview Area --}}
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        @if($this->imagePreview)
                            {{-- Preview Gambar Baru --}}
                            <div class="relative">
                                <img src="{{ $this->imagePreview }}" 
                                     alt="Preview gambar fasilitas baru"
                                     class="w-full h-48 object-cover rounded-lg"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                
                                {{-- Fallback jika preview gagal --}}
                                <div class="hidden w-full h-48 bg-gray-100 rounded-lg flex flex-col items-center justify-center">
                                    <x-icon name="o-photo" class="w-12 h-12 text-gray-400 mb-2" />
                                    <p class="text-sm text-gray-500">Gambar Baru Siap Diupload</p>
                                </div>
                                
                                {{-- Remove Button --}}
                                <button type="button" 
                                        wire:click="$set('gambar', null)"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <x-mary-icon name="o-x-mark" class="w-4 h-4" />
                                </button>
                                
                                {{-- New Image Badge --}}
                                <div class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">
                                    Gambar Baru
                                </div>
                            </div>
                        @elseif($this->currentImageUrl)
                            {{-- Gambar Saat Ini --}}
                            <div class="relative">
                                <img src="{{ $this->currentImageUrl }}" 
                                     alt="Gambar fasilitas saat ini"
                                     class="w-full h-48 object-cover rounded-lg"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                
                                {{-- Fallback jika gambar tidak bisa dimuat --}}
                                <div class="hidden w-full h-48 bg-gray-100 rounded-lg flex flex-col items-center justify-center">
                                    <x-mary-icon name="o-photo" class="w-12 h-12 text-gray-400 mb-2" />
                                    <p class="text-sm text-gray-500">Gambar tidak dapat dimuat</p>
                                </div>
                                
                                {{-- Current Image Badge --}}
                                <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                    Gambar Saat Ini
                                </div>
                                
                                {{-- Remove Current Image Button --}}
                                <button type="button" 
                                        wire:click="removeCurrentImage"
                                        wire:confirm="Yakin ingin menghapus gambar ini?"
                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                    <x-mary-icon name="o-trash" class="w-4 h-4" />
                                </button>
                            </div>
                        @else
                            {{-- No Image Placeholder --}}
                            <div class="text-center py-8">
                                <x-mary-icon name="o-cloud-arrow-up" class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                                <p class="text-sm text-gray-600 mb-2">Belum ada gambar</p>
                                <p class="text-xs text-gray-500">Upload gambar untuk menampilkan fasilitas dengan lebih menarik</p>
                            </div>
                        @endif
                    </div>

                    {{-- Upload Guidelines --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <x-mary-icon name="o-information-circle" class="w-5 h-5 text-blue-600 mt-0.5 mr-2" />
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Tips Upload Gambar:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Upload gambar baru akan mengganti gambar lama</li>
                                    <li>Gunakan foto dengan pencahayaan yang baik</li>
                                    <li>Resolusi minimal 800x600 pixel</li>
                                    <li>Gambar akan otomatis dioptimasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </x-mary-card>

            {{-- Informasi Fasilitas --}}
            <x-mary-card title="Informasi Fasilitas" subtitle="Data fasilitas saat ini">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between items-start">
                        <span class="text-gray-600">ID Fasilitas</span>
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">#{{ $facility->id }}</span>
                    </div>
                    
                    <div class="flex justify-between items-start">
                        <span class="text-gray-600">Dibuat</span>
                        <div class="text-right">
                            <p class="font-medium">{{ $facility->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $facility->created_at->format('H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-start">
                        <span class="text-gray-600">Terakhir Diubah</span>
                        <div class="text-right">
                            <p class="font-medium">{{ $facility->updated_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $facility->updated_at->format('H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($facility->gambar)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status Gambar</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <x-mary-icon name="o-check-circle" class="w-3 h-3 mr-1" />
                                Ada Gambar
                            </span>
                        </div>
                    @else
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Status Gambar</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <x-mary-icon name="o-exclamation-triangle" class="w-3 h-3 mr-1" />
                                Tanpa Gambar
                            </span>
                        </div>
                    @endif
                </div>
            </x-mary-card>

            {{-- Quick Actions --}}
            <x-mary-card title="Aksi Cepat" subtitle="Tindakan lainnya">
                <div class="space-y-2">
                    <x-mary-button 
                        label="Lihat di Website" 
                        icon="o-eye" 
                        class="w-full" 
                        link="#" 
                        external />
                    
                    <x-mary-button 
                        label="Duplikasi Fasilitas" 
                        icon="o-document-duplicate" 
                        class="w-full" 
                        wire:click="duplicate"
                        wire:confirm="Yakin ingin menduplikasi fasilitas ini?" />
                    
                    <x-mary-button 
                        label="Hapus Fasilitas" 
                        icon="o-trash" 
                        class="w-full btn-error" 
                        wire:click="delete"
                        wire:confirm="Yakin ingin menghapus fasilitas ini? Tindakan ini tidak dapat dibatalkan." />
                </div>
            </x-mary-card>
        </div>
    </div>
</div>