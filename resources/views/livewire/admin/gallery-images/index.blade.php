<div class="space-y-6" x-data="{
    reorderMode: @entangle('reorderMode'),
    imageOrder: @entangle('imageOrder'),
    showDeleteModal: @entangle('showDeleteModal')
}">
    {{-- Header Section --}}
    <x-mary-header title="Kelola Gambar Gallery" subtitle="{{ $gallery->judul }}" progress-indicator class="mb-6">
        <x-slot:actions>
            <div class="flex items-center gap-2">
                <x-mary-button icon="o-arrow-left" link="{{ route('admin.galleries.edit', $gallery->id) }}" class="btn-ghost btn-sm">
                    Kembali
                </x-mary-button>
                @if(!$reorderMode)
                    <button 
                        wire:click="toggleReorderMode" 
                        class="btn btn-outline btn-sm"
                        wire:loading.attr="disabled"
                    >
                        <x-mary-icon name="o-arrows-up-down" class="w-4 h-4" />
                        Atur Urutan
                    </button>
                @else
                    <button 
                        wire:click="saveOrder" 
                        class="btn btn-success btn-sm"
                        wire:loading.attr="disabled"
                    >
                        <x-mary-icon name="o-check" class="w-4 h-4" />
                        Simpan Urutan
                    </button>
                    <button 
                        wire:click="toggleReorderMode" 
                        class="btn btn-ghost btn-sm"
                        wire:loading.attr="disabled"
                    >
                        <x-mary-icon name="o-x-mark" class="w-4 h-4" />
                        Batal
                    </button>
                @endif
            </div>
        </x-slot:actions>
    </x-mary-header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-mary-stat 
            title="Total Gambar" 
            description="Jumlah gambar dalam gallery"
            value="{{ number_format($stats['total']) }}"
            icon="o-photo" 
            color="text-blue-500" 
        />
        
        <x-mary-stat 
            title="Gallery" 
            description="{{ $gallery->deskripsi ? Str::limit($gallery->deskripsi, 50) : 'Tidak ada deskripsi' }}"
            value="{{ $gallery->is_active ? 'Aktif' : 'Nonaktif' }}"
            icon="o-rectangle-stack" 
            color="{{ $gallery->is_active ? 'text-green-500' : 'text-red-500' }}" 
        />
    </div>

    {{-- Filter & Search Section --}}
    @if(!$reorderMode)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <x-mary-input 
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari gambar..."
                        icon="o-magnifying-glass"
                        clearable
                    />
                </div>
                
                {{-- Sort By --}}
                <div>
                    <x-mary-select 
                        wire:model.live="sortBy"
                        :options="collect($sortableColumns)->map(fn($label, $value) => ['id' => $value, 'name' => $label])->values()->toArray()"
                        placeholder="Urutkan berdasarkan"
                        option-value="id"
                        option-label="name"
                    />
                </div>
                
                {{-- Per Page --}}
                <div>
                    <x-mary-select 
                        wire:model.live="perPage"
                        :options="collect($perPageOptions)->map(fn($value) => ['id' => $value, 'name' => $value . ' per halaman'])->toArray()"
                        option-value="id"
                        option-label="name"
                    />
                </div>
            </div>
            
            {{-- Reset Filters --}}
            @if($search || $sortBy !== 'urutan' || $sortDirection !== 'asc' || $perPage !== 12)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button 
                        wire:click="resetFilters" 
                        class="btn btn-ghost btn-sm"
                        wire:loading.attr="disabled"
                    >
                        <x-mary-icon name="o-x-mark" class="w-4 h-4" />
                        Reset Filter
                    </button>
                </div>
            @endif
        </div>
    @endif

    {{-- Images Grid --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        @if($images->count() > 0)
            <div class="p-6">
                @if($reorderMode)
                    {{-- Reorder Mode Grid --}}
                    <div 
                        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4"
                        x-data="{
                            init() {
                                new Sortable(this.$el, {
                                    animation: 150,
                                    ghostClass: 'opacity-50',
                                    onEnd: (evt) => {
                                        const newOrder = Array.from(evt.to.children).map(el => el.dataset.imageId);
                                        this.imageOrder = newOrder;
                                    }
                                });
                            }
                        }"
                    >
                        @foreach($images as $image)
                            <div 
                                class="relative group cursor-move bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 hover:border-blue-400 transition-colors"
                                data-image-id="{{ $image->id }}"
                            >
                                <div class="aspect-square overflow-hidden rounded-lg">
                                    <img 
                                        src="{{ Storage::url($image->gambar) }}" 
                                        alt="Gallery Image {{ $image->urutan }}"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                    >
                                </div>
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center">
                                    <x-mary-icon name="o-arrows-up-down" class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition-opacity" />
                                </div>
                                <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">
                                    {{ $image->urutan }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Normal Mode Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                        @foreach($images as $image)
                            <div class="relative group">
                                <div class="aspect-square overflow-hidden rounded-lg bg-gray-100">
                                    <img 
                                        src="{{ Storage::url($image->gambar) }}" 
                                        alt="Gallery Image {{ $image->urutan }}"
                                        class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                                        loading="lazy"
                                        onerror="this.onerror=null; this.src='/images/placeholder-image.svg'; console.error('Gambar tidak dapat dimuat:', this.alt);"
                                    >
                                </div>
                                
                                {{-- Overlay Actions --}}
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <div class="flex items-center gap-2">
                                        {{-- Preview Button --}}
                                        <button 
                                            class="btn btn-sm btn-circle bg-white text-gray-700 hover:bg-gray-100"
                                            onclick="document.getElementById('preview_{{ $image->id }}').showModal()"
                                        >
                                            <x-mary-icon name="o-eye" class="w-4 h-4" />
                                        </button>
                                        
                                        {{-- Delete Button --}}
                                        <button 
                                            wire:click="confirmDelete('{{ $image->id }}', 'Gambar #{{ $image->urutan }}')"
                                            class="btn btn-sm btn-circle bg-red-500 text-white hover:bg-red-600"
                                        >
                                            <x-mary-icon name="o-trash" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                                
                                {{-- Image Info --}}
                                <div class="absolute bottom-2 left-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                                    #{{ $image->urutan }}
                                </div>
                                
                                {{-- Preview Modal --}}
                                <dialog id="preview_{{ $image->id }}" class="modal">
                                    <div class="modal-box max-w-4xl">
                                        <form method="dialog">
                                            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                                        </form>
                                        <h3 class="font-bold text-lg mb-4">Preview Gambar #{{ $image->urutan }}</h3>
                                        <div class="flex justify-center">
                                            <img 
                                                src="{{ Storage::url($image->gambar) }}" 
                                                alt="Gallery Image {{ $image->urutan }}"
                                                class="max-w-full max-h-96 object-contain rounded-lg"
                                                onerror="this.onerror=null; this.src='/images/placeholder-image.svg'; console.error('Gambar tidak dapat dimuat:', this.alt);"
                                            >
                                        </div>
                                        <div class="mt-4 text-sm text-gray-600">
                                            <p><strong>Urutan:</strong> {{ $image->urutan }}</p>
                                            <p><strong>Diupload:</strong> {{ $image->created_at->format('d M Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <form method="dialog" class="modal-backdrop">
                                        <button>close</button>
                                    </form>
                                </dialog>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            {{-- Pagination --}}
            @if(!$reorderMode && $images->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $images->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <x-mary-icon name="o-photo" class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 mb-2" style="font-family: 'Bricolage Grotesque', sans-serif;">
                    Belum Ada Gambar
                </h3>
                <p class="text-gray-600 mb-6" style="font-family: 'Inter', sans-serif;">
                    @if($search)
                        Tidak ada gambar yang sesuai dengan pencarian "{{ $search }}".
                    @else
                        Gallery ini belum memiliki gambar. Tambahkan gambar melalui halaman edit gallery.
                    @endif
                </p>
                @if($search)
                    <button 
                        wire:click="resetFilters" 
                        class="btn btn-outline btn-sm"
                    >
                        <x-mary-icon name="o-x-mark" class="w-4 h-4" />
                        Reset Pencarian
                    </button>
                @else
                    <button 
                        wire:click="back" 
                        class="btn btn-primary btn-sm"
                    >
                        <x-mary-icon name="o-arrow-left" class="w-4 h-4" />
                        Kembali ke Gallery
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus" subtitle="Tindakan ini tidak dapat dibatalkan">
        <div class="py-4">
            <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 rounded-lg">
                <x-mary-icon name="o-exclamation-triangle" class="w-6 h-6 text-red-500 flex-shrink-0" />
                <div>
                    <p class="text-sm text-red-800">
                        Apakah Anda yakin ingin menghapus <strong>{{ $deleteImageName }}</strong>?
                    </p>
                    <p class="text-xs text-red-600 mt-1">
                        File gambar akan dihapus secara permanen dari server.
                    </p>
                </div>
            </div>
        </div>
        
        <x-slot:actions>
            <x-mary-button 
                label="Batal" 
                wire:click="cancelDelete" 
                class="btn-ghost"
            />
            <x-mary-button 
                label="Hapus" 
                wire:click="delete" 
                class="btn-error"
                spinner="delete"
            />
        </x-slot:actions>
    </x-mary-modal>

    {{-- Loading Overlay --}}
    <div 
        wire:loading.flex 
        wire:target="delete,saveOrder,toggleReorderMode"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center"
    >
        <div class="bg-white rounded-lg p-6 flex items-center gap-3">
            <span class="loading loading-spinner loading-md"></span>
            <span class="text-gray-700" style="font-family: 'Inter', sans-serif;">Memproses...</span>
        </div>
    </div>
</div>

{{-- Sortable.js for reordering --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush