<div>
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Kegiatan Sekolah</h1>
            <p class="text-gray-600 mt-1">Buat kegiatan sekolah baru</p>
        </div>
        <x-mary-button label="Kembali" icon="o-arrow-left" class="btn-ghost" wire:click="cancel" />
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <x-mary-card title="Informasi Dasar" subtitle="Data utama kegiatan sekolah">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Slug --}}
                        <div class="md:col-span-1">
                            <x-mary-input 
                                wire:model.blur="slug"
                                label="Slug *"
                                placeholder="Contoh: lomba-karya-tulis-ilmiah"
                                hint="Slug akan otomatis ter-generate saat nama diisi. URL-friendly identifier untuk kegiatan"
                                maxlength="255" />
                            <div class="mt-2 flex items-center gap-2">
                                <x-mary-button 
                                    label="Generate Ulang" 
                                    icon="o-sparkles" 
                                    wire:click="generateSlug"
                                    class="btn-ghost btn-sm"
                                    type="button" />
                                <span class="text-xs text-gray-500">💡 Otomatis ter-generate dari nama</span>
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="md:col-span-1">
                            <x-mary-select 
                                wire:model.blur="kategori"
                                label="Kategori *"
                                :options="[
                                    ['id' => 'akademik', 'name' => 'Akademik'],
                                    ['id' => 'olahraga', 'name' => 'Olahraga'],
                                    ['id' => 'seni', 'name' => 'Seni & Budaya'],
                                    ['id' => 'teknologi', 'name' => 'Teknologi'],
                                    ['id' => 'sosial', 'name' => 'Sosial'],
                                    ['id' => 'keagamaan', 'name' => 'Keagamaan'],
                                    ['id' => 'lainnya', 'name' => 'Lainnya']
                                ]"
                                option-value="id"
                                option-label="name"
                                placeholder="Pilih kategori kegiatan"
                                hint="Kategori kegiatan sekolah" />
                        </div>
                    </div>

                    {{-- Nama Kegiatan --}}
                    <x-mary-input 
                        wire:model.blur="nama_kegiatan"
                        label="Nama Kegiatan *"
                        placeholder="Contoh: Lomba Karya Tulis Ilmiah Tingkat Nasional"
                        hint="Nama lengkap kegiatan sekolah"
                        maxlength="255" />

                    {{-- Deskripsi --}}
                    <x-mary-textarea 
                        wire:model.blur="deskripsi"
                        label="Deskripsi"
                        placeholder="Deskripsi singkat tentang kegiatan..."
                        hint="Penjelasan singkat tentang kegiatan (opsional)"
                        rows="4"
                        maxlength="5000" />

                    {{-- Lokasi --}}
                    <x-mary-input 
                        wire:model.blur="lokasi"
                        label="Lokasi"
                        placeholder="Contoh: Aula SMK Kesatrian, Jakarta"
                        hint="Lokasi pelaksanaan kegiatan (opsional)"
                        maxlength="255" />
                </x-mary-card>

                {{-- Jadwal Kegiatan --}}
                <x-mary-card title="Jadwal Kegiatan" subtitle="Waktu pelaksanaan kegiatan">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tanggal Mulai --}}
                        <div>
                            <x-mary-input 
                                wire:model.blur="tanggal_mulai"
                                label="Tanggal Mulai *"
                                type="date"
                                hint="Tanggal dimulainya kegiatan" />
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <x-mary-input 
                                wire:model.blur="tanggal_selesai"
                                label="Tanggal Selesai"
                                type="date"
                                hint="Tanggal berakhirnya kegiatan (opsional)" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Jam Mulai --}}
                        <div>
                            <x-mary-input 
                                wire:model.blur="jam_mulai"
                                label="Jam Mulai"
                                type="time"
                                hint="Jam dimulainya kegiatan (opsional)" />
                        </div>

                        {{-- Jam Selesai --}}
                        <div>
                            <x-mary-input 
                                wire:model.blur="jam_selesai"
                                label="Jam Selesai"
                                type="time"
                                hint="Jam berakhirnya kegiatan (opsional)" />
                        </div>
                    </div>
                </x-mary-card>

                {{-- Konten Kegiatan --}}
                <x-mary-card title="Konten Kegiatan" subtitle="Detail dan informasi lengkap kegiatan">
                    <x-mary-textarea 
                        wire:model.blur="konten"
                        label="Konten Lengkap"
                        placeholder="Tulis detail lengkap tentang kegiatan, tujuan, manfaat, dan informasi penting lainnya..."
                        hint="Konten lengkap kegiatan yang akan ditampilkan di website (opsional)"
                        rows="8" />
                </x-mary-card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Image Upload --}}
                <x-mary-card title="Gambar Kegiatan" subtitle="Upload gambar representatif">
                    <div class="space-y-4">
                        {{-- Image Preview --}}
                        @if($gambar_utama)
                            <div class="relative">
                                <img src="{{ $this->imagePreview }}" 
                                     alt="Preview"
                                     class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                <x-mary-button 
                                    icon="o-x-mark" 
                                    wire:click="removeImage"
                                    class="btn-sm btn-circle btn-error absolute top-2 right-2"
                                    type="button" />
                            </div>
                        @else
                            <div class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <x-mary-icon name="o-photo" class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                    <p class="text-sm text-gray-500">Belum ada gambar</p>
                                </div>
                            </div>
                        @endif

                        {{-- File Input --}}
                        <x-mary-file 
                            wire:model="gambar_utama"
                            label="Pilih Gambar"
                            hint="Format: JPG, PNG, GIF, WebP. Maksimal 2MB"
                            accept="image/*" />
                    </div>
                </x-mary-card>

                {{-- Pengaturan Kegiatan --}}
                <x-mary-card title="Pengaturan" subtitle="Konfigurasi kegiatan">
                    <div class="space-y-4">
                        {{-- Status Aktif --}}
                        <x-mary-toggle 
                            wire:model.live="aktif"
                            label="Kegiatan Aktif"
                            hint="Kegiatan dapat dilihat di website"
                            class="toggle-success" />

                        {{-- Kegiatan Unggulan --}}
                        <x-mary-toggle 
                            wire:model.live="unggulan"
                            label="Kegiatan Unggulan"
                            hint="Tampilkan sebagai kegiatan unggulan"
                            class="toggle-warning" />
                    </div>
                </x-mary-card>

                {{-- Meta Information --}}
                <x-mary-card title="Meta Information" subtitle="SEO dan informasi tambahan">
                    <div class="space-y-4">
                        {{-- Meta Title --}}
                        <x-mary-input 
                            wire:model.blur="meta_title"
                            label="Meta Title"
                            placeholder="Judul untuk SEO (opsional)"
                            hint="Judul yang akan muncul di hasil pencarian"
                            maxlength="60" />

                        {{-- Meta Description --}}
                        <x-mary-textarea 
                            wire:model.blur="meta_description"
                            label="Meta Description"
                            placeholder="Deskripsi untuk SEO (opsional)"
                            hint="Deskripsi yang akan muncul di hasil pencarian"
                            rows="3"
                            maxlength="160" />
                    </div>
                </x-mary-card>

                {{-- Action Buttons --}}
                <x-mary-card>
                    <div class="space-y-3">
                        <x-mary-button 
                            label="Simpan Kegiatan" 
                            icon="o-check" 
                            class="btn-primary w-full"
                            type="submit"
                            spinner="save" />
                        
                        <x-mary-button 
                            label="Batal" 
                            icon="o-x-mark" 
                            class="btn-ghost w-full"
                            wire:click="cancel"
                            type="button" />
                    </div>
                </x-mary-card>
            </div>
        </div>
    </form>
</div>