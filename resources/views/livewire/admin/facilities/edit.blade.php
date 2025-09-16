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
                        <x-mary-button label="Update Fasilitas" type="submit" icon="o-check" class="btn-primary"
                            spinner="update" />
                    </x-slot:actions>
                </x-mary-card>
            </x-mary-form>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Upload Gambar --}}
            <x-mary-card title="Gambar Fasilitas" subtitle="Upload hingga 5 foto fasilitas">
                <div class="space-y-4">
                    {{-- File Input --}}
                    <x-mary-file label="Pilih Gambar Baru (1-5 gambar)" wire:model="images" accept="image/*" multiple
                        hint="Format: JPG, PNG, GIF, WebP. Maksimal 2MB per file" />

                    {{-- Current Images --}}
                    @if (!empty($currentImages))
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-base-content">Gambar Saat Ini
                                ({{ count($currentImages) }})</h4>

                            <div class="space-y-4">
                                @foreach ($currentImages as $index => $image)
                                    <div
                                        class="bg-base-200 rounded-lg overflow-hidden border border-base-300 shadow-sm">
                                        {{-- Gambar --}}
                                        <div class="relative">
                                            <img src="{{ $image['url'] }}" alt="{{ $image['alt_text'] }}"
                                                class="w-full h-32 object-cover {{ $image['is_primary'] ? 'ring-2 ring-blue-500' : '' }}">

                                            {{-- Primary Badge --}}
                                            @if ($image['is_primary'])
                                                <div
                                                    class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-md shadow-sm">
                                                    Gambar Utama
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Action Buttons Area --}}
                                        <div class="p-3 bg-base-100">
                                            <div class="flex justify-between items-center gap-2">
                                                {{-- Set Primary Button --}}
                                                @if (!$image['is_primary'])
                                                    <button type="button"
                                                        wire:click="setPrimaryImage('{{ $image['id'] }}')"
                                                        class="btn btn-sm btn-outline btn-primary flex-1 hover:scale-105 transition-transform">
                                                        <x-mary-icon name="o-star" class="w-4 h-4 mr-1" />
                                                        Jadikan Utama
                                                    </button>
                                                @else
                                                    <div class="flex-1 text-center">
                                                        <span
                                                            class="text-sm text-blue-600 font-medium bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-full">
                                                            Gambar Utama
                                                        </span>
                                                    </div>
                                                @endif

                                                {{-- Delete Button - Modal confirmation --}}
                                                <button type="button"
                                                    wire:click="confirmDeleteImage('{{ $image['id'] }}')"
                                                    class="btn btn-sm btn-error hover:scale-105 transition-transform"
                                                    title="Hapus gambar">
                                                    <x-mary-icon name="o-trash" class="w-4 h-4 mr-1" />
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Preview Gambar Baru --}}
                    @if (!empty($this->imagePreviews))
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-base-content">Gambar Baru
                                ({{ count($this->imagePreviews) }})</h4>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach ($this->imagePreviews as $index => $preview)
                                    @if ($preview)
                                        <div class="relative">
                                            <img src="{{ $preview }}"
                                                alt="Preview gambar baru {{ $index + 1 }}"
                                                class="w-full h-24 object-cover rounded-lg border-2 border-green-500">

                                            {{-- New Badge --}}
                                            <div
                                                class="absolute top-1 left-1 bg-green-500 text-white text-xs px-1.5 py-0.5 rounded">
                                                Baru
                                            </div>

                                            {{-- Remove Button --}}
                                            <button type="button" wire:click="removePreviewImage({{ $index }})"
                                                class="absolute top-1 right-1 bg-red-500 text-white rounded p-1 hover:bg-red-600">
                                                <x-mary-icon name="o-x-mark" class="w-3 h-3" />
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Empty State --}}
                    @if (empty($currentImages) && empty($this->imagePreviews))
                        <div class="border-2 border-dashed border-base-300 rounded-lg p-8 text-center">
                            <x-mary-icon name="o-cloud-arrow-up" class="w-12 h-12 text-base-content/40 mx-auto mb-4" />
                            <p class="text-sm text-base-content/60 mb-2">Belum ada gambar</p>
                            <p class="text-xs text-base-content/50">Upload gambar untuk menampilkan fasilitas dengan
                                lebih menarik</p>
                        </div>
                    @endif

                    {{-- Upload Guidelines --}}
                    <div
                        class="bg-blue-50 dark:bg-blue-950/50 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                        <div class="flex items-start">
                            <x-mary-icon name="o-information-circle"
                                class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 mr-2" />
                            <div class="text-sm text-blue-800 dark:text-blue-200">
                                <p class="font-medium mb-1">Tips Upload Gambar:</p>
                                <ul class="list-disc list-inside space-y-1 text-xs">
                                    <li>Upload hingga 5 gambar sekaligus</li>
                                    <li>Klik tombol "Jadikan Utama" untuk mengatur gambar utama</li>
                                    <li>Gunakan foto dengan pencahayaan yang baik</li>
                                    <li>Resolusi minimal 800x600 pixel</li>
                                    <li>Gambar akan otomatis dioptimasi ke format WebP</li>
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
                        <span class="text-base-content/70">ID Fasilitas</span>
                        <span class="font-mono text-xs bg-base-200 px-2 py-1 rounded">#{{ $facility->id }}</span>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-base-content/70">Dibuat</span>
                        <div class="text-right">
                            <p class="font-medium text-base-content">{{ $facility->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-base-content/50">{{ $facility->created_at->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-start">
                        <span class="text-base-content/70">Terakhir Diubah</span>
                        <div class="text-right">
                            <p class="font-medium text-base-content">{{ $facility->updated_at->format('d M Y') }}</p>
                            <p class="text-xs text-base-content/50">{{ $facility->updated_at->format('H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-base-content/70">Jumlah Gambar</span>
                        @if (!empty($currentImages))
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                <x-mary-icon name="o-photo" class="w-3 h-3 mr-1" />
                                {{ count($currentImages) }} Gambar
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200">
                                <x-mary-icon name="o-exclamation-triangle" class="w-3 h-3 mr-1" />
                                Tanpa Gambar
                            </span>
                        @endif
                    </div>
                </div>
            </x-mary-card>

            {{-- Quick Actions --}}
            <x-mary-card title="Aksi Cepat" subtitle="Tindakan lainnya">
                <div class="space-y-2">
                    <x-mary-button label="Lihat di Website" icon="o-eye" class="w-full" link="#" external />

                    <x-mary-button label="Duplikasi Fasilitas" icon="o-document-duplicate" class="w-full"
                        wire:click="duplicate" />

                    <x-mary-button label="Hapus Fasilitas" icon="o-trash" class="w-full btn-error"
                        wire:click="delete" />
                </div>
            </x-mary-card>
        </div>
    </div>

    {{-- Dialog Konfirmasi Hapus Gambar --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus Gambar"
        subtitle="Tindakan ini tidak dapat dibatalkan" persistent>
        <div
            class="flex items-center space-x-3 p-4 bg-red-50 dark:bg-red-950/50 rounded-lg border border-red-200 dark:border-red-800">
            <div class="flex-shrink-0">
                <x-mary-icon name="o-exclamation-triangle" class="w-8 h-8 text-red-600 dark:text-red-400" />
            </div>
            <div>
                <h4 class="text-sm font-medium text-red-800 dark:text-red-200">Yakin ingin menghapus gambar ini?</h4>
                <p class="text-sm text-red-700 dark:text-red-300 mt-1">
                    Gambar akan dihapus secara permanen dari sistem dan tidak dapat dikembalikan.
                </p>
            </div>
        </div>

        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="cancelDeleteImage" class="btn-ghost" />
            <x-mary-button label="Hapus Gambar" wire:click="removeImage" class="btn-error" spinner="removeImage" />
        </x-slot:actions>
    </x-mary-modal>

    {{-- JavaScript untuk functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Facility Edit Page Loaded');
        });
    </script>

    {{-- Styles untuk improved UX --}}
    <style>
        /* Hover effects untuk buttons */
        .btn:hover {
            transform: translateY(-1px);
        }

        /* Loading state untuk images */
        img[src] {
            transition: opacity 0.3s ease-in-out;
        }

        img[src=""]:not([src]) {
            opacity: 0;
        }

        /* Modal animation */
        .modal {
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</div>
