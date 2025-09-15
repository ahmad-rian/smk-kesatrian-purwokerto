<div>
    {{-- Header Section --}}
    <x-mary-header title="Tambah Fasilitas" separator>
        <x-slot:actions>
            <x-mary-button label="Kembali" icon="o-arrow-left" link="{{ route('admin.facilities.index') }}" />
        </x-slot:actions>
    </x-mary-header>

    {{-- Form Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <x-mary-form wire:submit="save">
                <x-mary-card title="Informasi Fasilitas" subtitle="Lengkapi data fasilitas sekolah">
                    {{-- Nama Fasilitas --}}
                    <x-mary-input label="Nama Fasilitas" wire:model="nama" placeholder="Contoh: Laboratorium Komputer"
                        hint="Nama unik untuk mengidentifikasi fasilitas" required />

                    {{-- Program Studi --}}
                    <x-mary-select label="Program Studi" wire:model="study_program_id" :options="$studyPrograms"
                        option-value="id" option-label="nama" placeholder="Pilih Program Studi"
                        hint="Fasilitas ini digunakan untuk program studi mana?" required />

                    {{-- Kategori --}}
                    <x-mary-select label="Kategori Fasilitas" wire:model="kategori" :options="$this->kategoriOptions"
                        option-value="value" option-label="label" placeholder="Pilih Kategori (Opsional)"
                        hint="Kategori membantu mengelompokkan fasilitas" />

                    {{-- Deskripsi --}}
                    <x-mary-textarea label="Deskripsi Fasilitas" wire:model="deskripsi"
                        placeholder="Jelaskan detail fasilitas, peralatan yang tersedia, kapasitas, dll."
                        hint="Minimal 10 karakter, maksimal 1000 karakter" rows="5" required />

                    {{-- Form Actions --}}
                    <x-slot:actions>
                        <x-mary-button label="Batal" wire:click="cancel" />
                        <x-mary-button label="Simpan Fasilitas" type="submit" icon="o-check" class="btn-primary"
                            spinner="save" />
                    </x-slot:actions>
                </x-mary-card>
            </x-mary-form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Upload Multiple Gambar --}}
            <x-mary-card title="Gambar Fasilitas" subtitle="Upload 1-5 foto fasilitas">
                <div class="space-y-4">
                    {{-- File Input untuk Multiple Images --}}
                    <x-mary-file label="Pilih Gambar (1-5 file)" wire:model="images" accept="image/*" multiple
                        hint="Format: JPG, PNG, GIF, WebP. Maksimal 2MB per file" />

                    {{-- Preview Area untuk Multiple Images --}}
                    <div class="space-y-3">
                        @if (!empty($this->imagePreviews))
                            {{-- Preview Multiple Images --}}
                            <div class="grid grid-cols-2 gap-3">
                                @foreach ($this->imagePreviews as $index => $preview)
                                    @if ($preview)
                                        <div class="relative group">
                                            <img src="{{ $preview }}" alt="Preview gambar {{ $index + 1 }}"
                                                class="w-full h-32 object-cover rounded-lg border-2 border-gray-200"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">

                                            {{-- Fallback jika preview gagal --}}
                                            <div
                                                class="hidden w-full h-32 bg-gray-100 rounded-lg flex flex-col items-center justify-center border-2 border-gray-200">
                                                <x-mary-icon name="o-photo" class="w-8 h-8 text-gray-400 mb-1" />
                                                <p class="text-xs text-gray-500">Gambar {{ $index + 1 }}</p>
                                            </div>

                                            {{-- Remove Button --}}
                                            <button type="button" wire:click="removeImage({{ $index }})"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <x-mary-icon name="o-x-mark" class="w-3 h-3" />
                                            </button>

                                            {{-- Primary Badge --}}
                                            @if ($index === 0)
                                                <div
                                                    class="absolute bottom-1 left-1 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                                    Utama
                                                </div>
                                            @endif

                                            {{-- Image Number --}}
                                            <div
                                                class="absolute top-1 left-1 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                                {{ $index + 1 }}
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Image Count Info --}}
                            <div class="text-center">
                                <p class="text-sm text-gray-600">
                                    {{ count($this->imagePreviews) }} dari 5 gambar dipilih
                                </p>
                            </div>
                        @else
                            {{-- Upload Placeholder --}}
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                <div class="text-center">
                                    <x-mary-icon name="o-cloud-arrow-up" class="w-12 h-12 text-gray-400 mx-auto mb-4" />
                                    <p class="text-sm text-gray-600 mb-2">Belum ada gambar dipilih</p>
                                    <p class="text-xs text-gray-500">Upload 1-5 gambar untuk menampilkan fasilitas
                                        dengan lebih menarik</p>
                                    <p class="text-xs text-gray-400 mt-2">Gambar pertama akan menjadi gambar utama</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Upload Guidelines --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <div class="flex items-start">
                            <x-mary-icon name="o-information-circle" class="w-5 h-5 text-blue-600 mt-0.5 mr-2" />
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Tips Upload Multiple Gambar:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Upload 1-5 gambar fasilitas</li>
                                    <li>Gambar pertama akan menjadi gambar utama</li>
                                    <li>Gunakan foto dengan pencahayaan yang baik</li>
                                    <li>Resolusi minimal 800x600 pixel</li>
                                    <li>Semua gambar akan otomatis dioptimasi ke WebP</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </x-mary-card>

            {{-- Help Card --}}
            <x-mary-card title="Bantuan" subtitle="Panduan pengisian form">
                <div class="space-y-3 text-sm">
                    <div class="flex items-start gap-3">
                        <x-mary-icon name="o-light-bulb" class="w-5 h-5 text-yellow-500 mt-0.5" />
                        <div>
                            <p class="font-medium text-gray-900">Nama Fasilitas</p>
                            <p class="text-gray-600">Gunakan nama yang jelas dan mudah dikenali</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <x-mary-icon name="o-academic-cap" class="w-5 h-5 text-blue-500 mt-0.5" />
                        <div>
                            <p class="font-medium text-gray-900">Program Studi</p>
                            <p class="text-gray-600">Pilih program studi yang menggunakan fasilitas ini</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <x-mary-icon name="o-document-text" class="w-5 h-5 text-green-500 mt-0.5" />
                        <div>
                            <p class="font-medium text-gray-900">Deskripsi</p>
                            <p class="text-gray-600">Jelaskan peralatan, kapasitas, dan kegunaan fasilitas</p>
                        </div>
                    </div>
                </div>
            </x-mary-card>

            {{-- Quick Stats --}}
            <x-mary-card title="Statistik" subtitle="Data fasilitas saat ini">
                @php
                    $totalFacilities = \App\Models\Facility::count();
                    $facilitiesWithImages = \App\Models\Facility::whereNotNull('gambar')->count();
                    $facilitiesWithMultipleImages = \App\Models\Facility::whereHas('images')->count();
                @endphp

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total Fasilitas</span>
                        <span class="font-semibold">{{ $totalFacilities }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Dengan Gambar</span>
                        <span
                            class="font-semibold text-green-600">{{ $facilitiesWithImages + $facilitiesWithMultipleImages }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Multiple Gambar</span>
                        <span class="font-semibold text-blue-600">{{ $facilitiesWithMultipleImages }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tanpa Gambar</span>
                        <span
                            class="font-semibold text-orange-600">{{ $totalFacilities - $facilitiesWithImages - $facilitiesWithMultipleImages }}</span>
                    </div>
                </div>
            </x-mary-card>
        </div>
    </div>
</div>
