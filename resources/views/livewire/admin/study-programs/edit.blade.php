<div>
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Program Studi</h1>
            <p class="text-gray-600 mt-1">Perbarui data program studi</p>
        </div>
        <x-mary-button label="Kembali" icon="o-arrow-left" class="btn-ghost" wire:click="cancel" />
    </div>

    <form wire:submit="update">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Basic Information --}}
                <x-mary-card title="Informasi Dasar" subtitle="Data utama program studi">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Kode Program --}}
                        <div class="md:col-span-1">
                            <x-mary-input wire:model.blur="kode" label="Kode Program *"
                                placeholder="Contoh: TKJ, RPL, MM"
                                hint="Kode unik untuk program studi (huruf kapital dan angka)" maxlength="10" />
                            <div class="mt-2">
                                <x-mary-button label="Generate Otomatis" icon="o-sparkles" wire:click="generateKode"
                                    class="btn-ghost btn-sm" type="button" />
                            </div>
                        </div>

                        {{-- Urutan --}}
                        <div class="md:col-span-1">
                            <x-mary-input wire:model.blur="urutan" label="Urutan Tampil *" type="number" min="1"
                                hint="Urutan tampil di website" />
                        </div>
                    </div>

                    {{-- Nama Program --}}
                    <x-mary-input wire:model.blur="nama" label="Nama Program Studi *"
                        placeholder="Contoh: Teknik Komputer dan Jaringan" hint="Nama lengkap program studi"
                        maxlength="255" />

                    {{-- Deskripsi --}}
                    <x-mary-textarea wire:model.blur="deskripsi" label="Deskripsi"
                        placeholder="Deskripsi singkat tentang program studi..."
                        hint="Penjelasan singkat tentang program studi (opsional)" rows="4" maxlength="5000" />

                    {{-- Ketua Program --}}
                    <x-mary-input wire:model.blur="ketua_program" label="Ketua Program"
                        placeholder="Nama ketua program studi" hint="Nama ketua program studi (opsional)"
                        maxlength="255" />
                </x-mary-card>

                {{-- Kompetensi --}}
                <x-mary-card title="Kompetensi" subtitle="Kompetensi yang akan dicapai siswa">
                    <div class="space-y-3">
                        @foreach ($kompetensi as $index => $item)
                            <div class="flex gap-2 items-start">
                                <div class="flex-1">
                                    <x-mary-input wire:model.blur="kompetensi.{{ $index }}"
                                        placeholder="Contoh: Mampu menginstal dan mengkonfigurasi sistem operasi"
                                        maxlength="500" />
                                </div>
                                <div class="flex gap-1 mt-2">
                                    @if ($index === count($kompetensi) - 1)
                                        <x-mary-button icon="o-plus" wire:click="addKompetensi"
                                            class="btn-ghost btn-sm" type="button" tooltip="Tambah kompetensi" />
                                    @endif
                                    @if (count($kompetensi) > 1)
                                        <x-mary-button icon="o-trash" wire:click="removeKompetensi({{ $index }})"
                                            class="btn-ghost btn-sm text-red-500" type="button"
                                            tooltip="Hapus kompetensi" />
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-sm text-gray-500 mt-2">
                        <x-mary-icon name="o-information-circle" class="w-4 h-4 inline mr-1" />
                        Tambahkan kompetensi yang akan dicapai siswa setelah lulus
                    </div>
                </x-mary-card>

                {{-- Prospek Karir --}}
                <x-mary-card title="Prospek Karir" subtitle="Peluang karir setelah lulus">
                    <div class="space-y-3">
                        @foreach ($prospek_karir as $index => $item)
                            <div class="flex gap-2 items-start">
                                <div class="flex-1">
                                    <x-mary-input wire:model.blur="prospek_karir.{{ $index }}"
                                        placeholder="Contoh: Network Administrator, IT Support, System Administrator"
                                        maxlength="500" />
                                </div>
                                <div class="flex gap-1 mt-2">
                                    @if ($index === count($prospek_karir) - 1)
                                        <x-mary-button icon="o-plus" wire:click="addProspekKarir"
                                            class="btn-ghost btn-sm" type="button" tooltip="Tambah prospek karir" />
                                    @endif
                                    @if (count($prospek_karir) > 1)
                                        <x-mary-button icon="o-trash"
                                            wire:click="removeProspekKarir({{ $index }})"
                                            class="btn-ghost btn-sm text-red-500" type="button"
                                            tooltip="Hapus prospek karir" />
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-sm text-gray-500 mt-2">
                        <x-mary-icon name="o-information-circle" class="w-4 h-4 inline mr-1" />
                        Tambahkan peluang karir yang tersedia setelah lulus
                    </div>
                </x-mary-card>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Image Upload --}}
                <x-mary-card title="Gambar Program" subtitle="Upload gambar representatif">
                    <div class="space-y-4">
                        {{-- Current Image --}}
                        @if ($currentImage && !$gambar)
                            <div class="relative">
                                <img src="{{ $this->currentImageUrl }}" alt="Gambar saat ini"
                                    class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                <x-mary-button icon="o-trash" wire:click="removeCurrentImage"
                                    class="btn-sm btn-circle btn-error absolute top-2 right-2" type="button"
                                    tooltip="Hapus gambar saat ini" />

                            </div>
                        @endif

                        {{-- New Image Preview --}}
                        @if ($gambar)
                            <div class="relative">
                                <img src="{{ $this->imagePreview }}" alt="Preview gambar baru"
                                    class="w-full h-48 object-cover rounded-lg border border-gray-200">
                                <x-mary-button icon="o-x-mark" wire:click="removeImage"
                                    class="btn-sm btn-circle btn-error absolute top-2 right-2" type="button" />
                                <div class="absolute bottom-2 left-2">
                                    <x-mary-badge value="Gambar Baru" class="badge-success" />
                                </div>
                            </div>
                        @endif

                        {{-- No Image State --}}
                        @if (!$currentImage && !$gambar)
                            <div
                                class="w-full h-48 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <x-mary-icon name="o-photo" class="w-12 h-12 text-gray-400 mx-auto mb-2" />
                                    <p class="text-sm text-gray-500">Belum ada gambar</p>
                                </div>
                            </div>
                        @endif

                        {{-- File Input --}}
                        <x-mary-file wire:model="gambar" label="{{ $currentImage ? 'Ganti Gambar' : 'Pilih Gambar' }}"
                            hint="Format: JPG, PNG, GIF, WebP. Maksimal 2MB" accept="image/*" />
                    </div>
                </x-mary-card>

                {{-- Theme Color --}}
                <x-mary-card title="Warna Tema" subtitle="Pilih warna representatif">
                    <div class="space-y-4">
                        {{-- Color Preview --}}
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-lg border border-gray-200"
                                style="background-color: {{ $warna }}"></div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $warna }}</div>
                                <div class="text-sm text-gray-500">Warna tema program</div>
                            </div>
                        </div>

                        {{-- Color Input --}}
                        <x-mary-input wire:model.live="warna" label="Kode Warna *" type="color"
                            hint="Pilih warna tema untuk program studi" />

                        {{-- Preset Colors --}}
                        <div class="grid grid-cols-6 gap-2">
                            @php
                                $presetColors = [
                                    '#3b82f6',
                                    '#ef4444',
                                    '#10b981',
                                    '#f59e0b',
                                    '#8b5cf6',
                                    '#06b6d4',
                                    '#84cc16',
                                    '#f97316',
                                    '#ec4899',
                                    '#6b7280',
                                ];
                            @endphp
                            @foreach ($presetColors as $color)
                                <button type="button" wire:click="$set('warna', '{{ $color }}')"
                                    class="w-8 h-8 rounded border-2 {{ $warna === $color ? 'border-gray-800' : 'border-gray-200' }} hover:border-gray-400"
                                    style="background-color: {{ $color }}"></button>
                            @endforeach
                        </div>
                    </div>
                </x-mary-card>

                {{-- Status --}}
                <x-mary-card title="Status" subtitle="Pengaturan status program">
                    <x-mary-toggle wire:model.live="aktif" label="Program Aktif"
                        hint="Program studi dapat dilihat di website" class="toggle-success" />
                </x-mary-card>

                {{-- Action Buttons --}}
                <x-mary-card>
                    <div class="space-y-3">
                        <x-mary-button label="Perbarui Program Studi" icon="o-check" class="btn-primary w-full"
                            type="submit" spinner="update" />

                        <x-mary-button label="Batal" icon="o-x-mark" class="btn-ghost w-full" wire:click="cancel"
                            type="button" />
                    </div>
                </x-mary-card>
            </div>
        </div>
    </form>
</div>
