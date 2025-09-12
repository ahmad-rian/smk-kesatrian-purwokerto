<div>
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Kegiatan Sekolah</h1>
            <p class="text-gray-600 mt-1">Perbarui data kegiatan sekolah</p>
        </div>
        <x-mary-button label="Kembali" icon="o-arrow-left" class="btn-ghost" wire:click="cancel" />
    </div>

    <form wire:submit="update">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <x-mary-card title="Informasi Dasar" subtitle="Data utama kegiatan sekolah">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Slug --}}
                        <div class="md:col-span-1">
                            <x-mary-input wire:model.blur="slug" label="Slug *"
                                placeholder="Contoh: lomba-karya-tulis-ilmiah"
                                hint="URL-friendly identifier untuk kegiatan" maxlength="255" />
                            <div class="mt-2">
                                <x-mary-button label="Generate Otomatis" icon="o-sparkles" wire:click="generateSlug"
                                    class="btn-ghost btn-sm" type="button" />
                            </div>
                        </div>

                        {{-- Kategori --}}
                        <div class="md:col-span-1">
                            <x-mary-select wire:model.blur="kategori" label="Kategori *" :options="[
                                ['id' => 'akademik', 'name' => 'Akademik'],
                                ['id' => 'olahraga', 'name' => 'Olahraga'],
                                ['id' => 'seni', 'name' => 'Seni & Budaya'],
                                ['id' => 'teknologi', 'name' => 'Teknologi'],
                                ['id' => 'sosial', 'name' => 'Sosial'],
                                ['id' => 'keagamaan', 'name' => 'Keagamaan'],
                                ['id' => 'lainnya', 'name' => 'Lainnya'],
                            ]"
                                option-value="id" option-label="name" placeholder="Pilih kategori kegiatan"
                                hint="Kategori kegiatan sekolah" />
                        </div>
                    </div>

                    {{-- Nama Kegiatan --}}
                    <x-mary-input wire:model.blur="nama_kegiatan" label="Nama Kegiatan *"
                        placeholder="Contoh: Lomba Karya Tulis Ilmiah Tingkat Nasional"
                        hint="Nama lengkap kegiatan sekolah" maxlength="255" />

                    {{-- Deskripsi --}}
                    <x-mary-textarea wire:model.blur="deskripsi" label="Deskripsi"
                        placeholder="Deskripsi singkat tentang kegiatan..."
                        hint="Penjelasan singkat tentang kegiatan (opsional)" rows="4" maxlength="5000" />

                    {{-- Lokasi --}}
                    <x-mary-input wire:model.blur="lokasi" label="Lokasi"
                        placeholder="Contoh: Aula SMK Kesatrian, Jakarta" hint="Lokasi pelaksanaan kegiatan (opsional)"
                        maxlength="255" />
                </x-mary-card>

                {{-- Jadwal Kegiatan --}}
                <x-mary-card title="Jadwal Kegiatan" subtitle="Waktu pelaksanaan kegiatan">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Tanggal Mulai --}}
                        <div>
                            <x-mary-input wire:model.blur="tanggal_mulai" label="Tanggal Mulai *" type="date"
                                hint="Tanggal dimulainya kegiatan" />
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <x-mary-input wire:model.blur="tanggal_selesai" label="Tanggal Selesai" type="date"
                                hint="Tanggal berakhirnya kegiatan (opsional)" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Jam Mulai --}}
                        <div>
                            <x-mary-input wire:model.blur="jam_mulai" label="Jam Mulai" type="time"
                                hint="Jam dimulainya kegiatan (opsional)" />
                        </div>

                        {{-- Jam Selesai --}}
                        <div>
                            <x-mary-input wire:model.blur="jam_selesai" label="Jam Selesai" type="time"
                                hint="Jam berakhirnya kegiatan (opsional)" />
                        </div>
                    </div>
                </x-mary-card>

                {{-- Konten Kegiatan --}}
                <x-mary-card title="Konten Kegiatan" subtitle="Detail dan informasi lengkap kegiatan">
                    <x-mary-textarea wire:model.blur="konten" label="Konten Lengkap"
                        placeholder="Tulis detail lengkap tentang kegiatan, tujuan, manfaat, dan informasi penting lainnya..."
                        hint="Konten lengkap kegiatan yang akan ditampilkan di website (opsional)" rows="8" />
                </x-mary-card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Image Upload --}}
                {{-- Image Upload - Fixed Version --}}
                <x-mary-card title="Gambar Kegiatan" subtitle="Upload gambar representatif">
                    <div class="space-y-4">
                        {{-- New Image Preview (Priority) --}}
                        @if ($gambar_utama)
                            <div class="relative">
                                @if ($this->imagePreview)
                                    <img src="{{ $this->imagePreview }}" alt="Preview gambar baru"
                                        class="w-full h-48 object-cover rounded-lg border-2 border-green-200"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                @endif

                                {{-- Fallback jika preview gagal --}}
                                <div
                                    class="w-full h-48 border-2 border-dashed border-green-300 rounded-lg flex items-center justify-center bg-green-50 {{ $this->imagePreview ? 'hidden' : '' }}">
                                    <div class="text-center py-6">
                                        <x-mary-icon name="o-photo" class="w-16 h-16 text-green-400 mx-auto mb-3" />
                                        <p class="text-sm font-medium text-green-600 mb-1">Gambar Siap Diupload</p>
                                        <p class="text-xs text-green-500">
                                            {{ $gambar_utama->getClientOriginalName() ?? 'File gambar terpilih' }}</p>
                                    </div>
                                </div>

                                {{-- Badge --}}
                                <div class="absolute top-3 left-3">
                                    <x-mary-badge value="Gambar Baru" class="badge-success badge-sm" />
                                </div>
                            </div>
                            {{-- Current Image (if no new image) --}}
                        @elseif($currentImage)
                            <div class="relative">
                                <img src="{{ $this->currentImageUrl }}" alt="Gambar saat ini"
                                    class="w-full h-48 object-cover rounded-lg border border-gray-200">

                                {{-- Badge --}}
                                <div class="absolute top-3 left-3">
                                    <x-mary-badge value="Gambar Saat Ini" class="badge-info badge-sm" />
                                </div>
                            </div>
                            {{-- No Image State --}}
                        @else
                            <div
                                class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center bg-gray-50">
                                <div class="text-center py-6">
                                    <x-mary-icon name="o-photo" class="w-16 h-16 text-gray-400 mx-auto mb-3" />
                                    <p class="text-sm font-medium text-gray-600 mb-1">Belum ada gambar</p>
                                    <p class="text-xs text-gray-500">Upload gambar untuk kegiatan</p>
                                </div>
                            </div>
                        @endif

                        {{-- File Input --}}
                        <div class="space-y-2">
                            <x-mary-file wire:model="gambar_utama"
                                label="{{ $currentImage || $gambar_utama ? 'Ganti Gambar' : 'Pilih Gambar' }}"
                                hint="Format: JPG, PNG, GIF, WebP. Maksimal 2MB" accept="image/*" />

                            {{-- Upload status --}}
                            @if ($gambar_utama)
                                <div class="flex items-center gap-2 text-sm text-green-600 bg-green-50 p-2 rounded">
                                    <x-mary-icon name="o-check-circle" class="w-4 h-4" />
                                    <span>Gambar siap diupload</span>
                                </div>
                            @endif
                        </div>

                        {{-- Action Buttons - Moved Below --}}
                        @if ($gambar_utama || $currentImage)
                            <div class="flex gap-2">
                                @if ($gambar_utama)
                                    <x-mary-button label="Hapus Gambar Baru" icon="o-x-mark" wire:click="removeImage"
                                        class="btn-outline btn-error btn-sm flex-1" type="button" />
                                @endif

                                @if ($currentImage)
                                    <x-mary-button label="Hapus Gambar Saat Ini" icon="o-trash"
                                        wire:click="removeCurrentImage" class="btn-outline btn-error btn-sm flex-1"
                                        type="button" />
                                @endif
                            </div>
                        @endif

                        {{-- Tips --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <div class="flex items-start gap-2">
                                <x-mary-icon name="o-information-circle"
                                    class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" />
                                <div class="text-xs text-blue-700">
                                    <p class="font-medium mb-1">Tips gambar yang baik:</p>
                                    <ul class="space-y-0.5">
                                        <li>• Resolusi minimal 800x600 pixel</li>
                                        <li>• Gambar yang merepresentasikan kegiatan</li>
                                        <li>• Hindari gambar yang terlalu gelap</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-mary-card>

                {{-- Pengaturan Kegiatan --}}
                <x-mary-card title="Pengaturan" subtitle="Konfigurasi kegiatan">
                    <div class="space-y-4">
                        {{-- Status Aktif --}}
                        <x-mary-toggle wire:model.live="aktif" label="Kegiatan Aktif"
                            hint="Kegiatan dapat dilihat di website" class="toggle-success" />

                        {{-- Kegiatan Unggulan --}}
                        <x-mary-toggle wire:model.live="unggulan" label="Kegiatan Unggulan"
                            hint="Tampilkan sebagai kegiatan unggulan" class="toggle-warning" />
                    </div>
                </x-mary-card>

                {{-- Meta Information --}}
                <x-mary-card title="Meta Information" subtitle="SEO dan informasi tambahan">
                    <div class="space-y-4">
                        {{-- Meta Title --}}
                        <x-mary-input wire:model.blur="meta_title" label="Meta Title"
                            placeholder="Judul untuk SEO (opsional)" hint="Judul yang akan muncul di hasil pencarian"
                            maxlength="60" />

                        {{-- Meta Description --}}
                        <x-mary-textarea wire:model.blur="meta_description" label="Meta Description"
                            placeholder="Deskripsi untuk SEO (opsional)"
                            hint="Deskripsi yang akan muncul di hasil pencarian" rows="3" maxlength="160" />
                    </div>
                </x-mary-card>

                {{-- Action Buttons --}}
                <x-mary-card>
                    <div class="space-y-3">
                        <x-mary-button label="Perbarui Kegiatan" icon="o-check" class="btn-primary w-full"
                            type="submit" spinner="update" />

                        <x-mary-button label="Batal" icon="o-x-mark" class="btn-ghost w-full" wire:click="cancel"
                            type="button" />

                        <x-mary-button label="Hapus Kegiatan" icon="o-trash" class="btn-error w-full"
                            wire:click="$dispatch('confirm-delete')" type="button" />
                    </div>
                </x-mary-card>
            </div>
        </div>
    </form>

    {{-- Modal Konfirmasi Hapus --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus"
        subtitle="Apakah Anda yakin ingin menghapus kegiatan ini?">
        <div class="space-y-4">
            <div class="alert alert-warning">
                <x-mary-icon name="o-exclamation-triangle" class="w-6 h-6" />
                <div>
                    <h3 class="font-bold">Peringatan!</h3>
                    <div class="text-xs">Data yang dihapus tidak dapat dikembalikan. Pastikan Anda yakin dengan
                        tindakan ini.</div>
                </div>
            </div>

            <p class="text-sm text-gray-600">
                Kegiatan <strong>{{ $nama_kegiatan }}</strong> akan dihapus secara permanen beserta semua data terkait.
            </p>
        </div>

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="$set('showDeleteModal', false)" />
            <x-mary-button label="Ya, Hapus" class="btn-error" wire:click="delete" spinner="delete" />
        </x-slot:actions>
    </x-mary-modal>
</div>
