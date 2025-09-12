<div>
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="title text-2xl font-bold text-base-content">Edit Pengaturan Situs</h1>
            <p class="body text-base-content/70 mt-1">Perbarui informasi dasar sekolah dan pengaturan situs</p>
        </div>
        
        <div class="flex items-center gap-2">
            <x-mary-button 
                icon="o-trash" 
                class="btn-error btn-outline" 
                wire:click="delete"
                wire:confirm="Apakah Anda yakin ingin menghapus pengaturan ini?"
                spinner="delete">
                Hapus
            </x-mary-button>
            
            <x-mary-button 
                icon="o-arrow-left" 
                class="btn-ghost" 
                wire:click="back"
                spinner="back">
                Kembali
            </x-mary-button>
        </div>
    </div>

    <!-- Form Section -->
    <form wire:submit="update">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Basic Information Card -->
            <x-mary-card title="Informasi Dasar" class="h-fit">
                <div class="space-y-4">
                    <!-- Nama Sekolah -->
                    <x-mary-input 
                        label="Nama Sekolah" 
                        wire:model="nama_sekolah" 
                        placeholder="Masukkan nama sekolah"
                        required
                        class="input-bordered" />
                    
                    <!-- Nama Singkat -->
                    <x-mary-input 
                        label="Nama Singkat" 
                        wire:model="nama_singkat" 
                        placeholder="Contoh: SMK Kesatrian"
                        class="input-bordered" />
                    
                    <!-- Tahun Berdiri -->
                    <x-mary-input 
                        label="Tahun Berdiri" 
                        type="number"
                        wire:model="tahun_berdiri" 
                        placeholder="Contoh: 1995"
                        min="1900"
                        max="{{ date('Y') }}"
                        class="input-bordered" />
                    
                    <!-- Tagline -->
                    <x-mary-input 
                        label="Tagline" 
                        wire:model="tagline" 
                        placeholder="Slogan atau motto sekolah"
                        class="input-bordered" />
                    
                    <!-- Deskripsi -->
                    <x-mary-textarea 
                        label="Deskripsi Sekolah" 
                        wire:model="deskripsi" 
                        placeholder="Deskripsi singkat tentang sekolah"
                        rows="3"
                        class="textarea-bordered" />
                    
                    <!-- Alamat -->
                    <x-mary-textarea 
                        label="Alamat" 
                        wire:model="alamat" 
                        placeholder="Masukkan alamat lengkap sekolah"
                        rows="3"
                        class="textarea-bordered" />
                    
                    <!-- Telepon -->
                    <x-mary-input 
                        label="Telepon" 
                        wire:model="telepon" 
                        placeholder="Contoh: (021) 1234567"
                        class="input-bordered" />
                    
                    <!-- Email -->
                    <x-mary-input 
                        label="Email" 
                        type="email"
                        wire:model="email" 
                        placeholder="Contoh: info@sekolah.sch.id"
                        class="input-bordered" />
                    
                    <!-- Website -->
                    <x-mary-input 
                        label="Website" 
                        type="url"
                        wire:model="website" 
                        placeholder="https://www.sekolah.sch.id"
                        class="input-bordered" />
                </div>
            </x-mary-card>

            <!-- Upload Images Card -->
            <x-mary-card title="Logo & Gambar" class="h-fit">
                <div class="space-y-4">
                    <!-- Logo Upload -->
                    <div>
                        <x-mary-file 
                            label="Logo Sekolah" 
                            wire:model="logo" 
                            accept="image/*"
                            hint="Format: JPG, PNG, WebP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah" />
                        
                        <div class="mt-2 flex items-center gap-4">
                            @if($logo)
                                <div>
                                    <p class="text-sm text-base-content/70 mb-1">Preview Baru:</p>
                                    <img src="{{ $logo->temporaryUrl() }}" 
                                         alt="Preview Logo Baru" 
                                         class="w-20 h-20 object-contain bg-base-200 rounded-lg p-2">
                                </div>
                            @endif
                            
                            @if($currentLogo)
                                <div>
                                    <p class="text-sm text-base-content/70 mb-1">Logo Saat Ini:</p>
                                    <img src="{{ Storage::url($currentLogo) }}" 
                                         alt="Logo Saat Ini" 
                                         class="w-20 h-20 object-contain bg-base-200 rounded-lg p-2">
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Nama Kepala Sekolah -->
                    <x-mary-input 
                        label="Nama Kepala Sekolah" 
                        wire:model="nama_kepala_sekolah" 
                        placeholder="Masukkan nama kepala sekolah"
                        class="input-bordered" />
                    
                    <!-- Foto Kepala Sekolah Upload -->
                    <div>
                        <x-mary-file 
                            label="Foto Kepala Sekolah" 
                            wire:model="foto_kepala_sekolah" 
                            accept="image/*"
                            hint="Format: JPG, PNG, WebP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah" />
                        
                        <div class="mt-2 flex items-center gap-4">
                            @if($foto_kepala_sekolah)
                                <div>
                                    <p class="text-sm text-base-content/70 mb-1">Preview Baru:</p>
                                    <img src="{{ $foto_kepala_sekolah->temporaryUrl() }}" 
                                         alt="Preview Foto Kepala Sekolah Baru" 
                                         class="w-20 h-20 object-cover rounded-lg">
                                </div>
                            @endif
                            
                            @if($currentFotoKepalaSekolah)
                                <div>
                                    <p class="text-sm text-base-content/70 mb-1">Foto Saat Ini:</p>
                                    <img src="{{ Storage::url($currentFotoKepalaSekolah) }}" 
                                         alt="Foto Kepala Sekolah Saat Ini" 
                                         class="w-20 h-20 object-cover rounded-lg">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-mary-card>
        </div>

        <!-- Vision & Mission Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            <x-mary-card title="Visi" class="h-fit">
                <x-mary-textarea 
                    label="Visi Sekolah" 
                    wire:model="visi" 
                    placeholder="Masukkan visi sekolah"
                    rows="5"
                    class="textarea-bordered" />
            </x-mary-card>

            <x-mary-card title="Misi" class="h-fit">
                <x-mary-textarea 
                    label="Misi Sekolah" 
                    wire:model="misi" 
                    placeholder="Masukkan misi sekolah"
                    rows="5"
                    class="textarea-bordered" />
            </x-mary-card>
        </div>

        <!-- Social Media Section -->
        <x-mary-card title="Media Sosial" class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Instagram -->
                <x-mary-input 
                    label="Instagram" 
                    wire:model="instagram" 
                    placeholder="https://instagram.com/username"
                    prefix="@"
                    class="input-bordered" />
                
                <!-- Facebook -->
                <x-mary-input 
                    label="Facebook" 
                    wire:model="facebook" 
                    placeholder="https://facebook.com/page"
                    prefix="f"
                    class="input-bordered" />
                
                <!-- YouTube -->
                <x-mary-input 
                    label="YouTube" 
                    wire:model="youtube" 
                    placeholder="https://youtube.com/channel"
                    prefix="▶"
                    class="input-bordered" />
                
                <!-- TikTok -->
                <x-mary-input 
                    label="TikTok" 
                    wire:model="tiktok" 
                    placeholder="https://tiktok.com/@username"
                    prefix="♪"
                    class="input-bordered" />
            </div>
        </x-mary-card>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 mt-8">
            <x-mary-button 
                type="button"
                class="btn-ghost" 
                wire:click="back"
                spinner="back">
                Batal
            </x-mary-button>
            
            <x-mary-button 
                type="submit"
                class="btn-primary" 
                spinner="update">
                <x-mary-icon name="o-check" class="w-4 h-4 mr-2" />
                Perbarui Pengaturan
            </x-mary-button>
        </div>
    </form>
</div>