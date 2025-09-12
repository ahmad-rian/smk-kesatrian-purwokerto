<?php

namespace App\Livewire\Admin\GalleryImages;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Services\ImageConversionService;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Livewire Component untuk mengelola Gallery Images
 * 
 * Fitur yang tersedia:
 * - Tampilkan daftar gambar dalam gallery tertentu
 * - Hapus gambar dari gallery
 * - Reorder urutan gambar
 * - Filter dan pencarian gambar
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination, Toast;

    /**
     * Gallery yang sedang dikelola
     */
    public Gallery $gallery;

    /**
     * Properties untuk filter dan pencarian
     */
    #[Url(as: 'search')]
    public string $search = '';

    #[Url(as: 'per_page')]
    public int $perPage = 12;

    #[Url(as: 'sort_by')]
    public string $sortBy = 'urutan';

    #[Url(as: 'sort_direction')]
    public string $sortDirection = 'asc';

    /**
     * Properties untuk modal delete
     */
    public bool $showDeleteModal = false;
    public ?string $deleteImageId = null;
    public string $deleteImageName = '';

    /**
     * Properties untuk reorder
     */
    public bool $reorderMode = false;
    public array $imageOrder = [];

    /**
     * Service dependencies
     */
    private ImageConversionService $imageService;

    /**
     * Boot component dengan dependency injection
     */
    public function boot(ImageConversionService $imageService): void
    {
        $this->imageService = $imageService;
    }

    /**
     * Mount component dengan gallery
     */
    public function mount(Gallery $gallery): void
    {
        $this->gallery = $gallery;
    }

    /**
     * Reset pagination saat search berubah
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat perPage berubah
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Method untuk konfirmasi hapus gambar
     */
    public function confirmDelete(string $imageId, string $imageName = ''): void
    {
        $this->deleteImageId = $imageId;
        $this->deleteImageName = $imageName ?: "Gambar #{$imageId}";
        $this->showDeleteModal = true;
    }

    /**
     * Method untuk membatalkan hapus
     */
    public function cancelDelete(): void
    {
        $this->deleteImageId = null;
        $this->deleteImageName = '';
        $this->showDeleteModal = false;
    }

    /**
     * Method untuk menghapus gambar
     */
    public function delete(): void
    {
        try {
            if (!$this->deleteImageId) {
                throw new \Exception('ID gambar tidak valid.');
            }

            $image = GalleryImage::findOrFail($this->deleteImageId);
            
            // Pastikan gambar milik gallery yang benar
            if ($image->gallery_id !== $this->gallery->id) {
                throw new \Exception('Gambar tidak ditemukan dalam gallery ini.');
            }

            // Hapus file gambar dari storage
            if ($image->gambar) {
                $this->imageService->deleteOldImage($image->gambar);
            }

            // Hapus record dari database
            $image->delete();

            $this->success(
                title: 'Berhasil!',
                description: 'Gambar berhasil dihapus dari gallery.',
                position: 'toast-top toast-end'
            );

            $this->cancelDelete();

        } catch (\Exception $e) {
            Log::error('Error deleting gallery image: ' . $e->getMessage(), [
                'image_id' => $this->deleteImageId,
                'gallery_id' => $this->gallery->id
            ]);

            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menghapus gambar.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk toggle reorder mode
     */
    public function toggleReorderMode(): void
    {
        $this->reorderMode = !$this->reorderMode;
        
        if ($this->reorderMode) {
            // Load current order
            $this->imageOrder = $this->gallery->images()->ordered()->pluck('id')->toArray();
        } else {
            // Reset order array
            $this->imageOrder = [];
        }
    }

    /**
     * Method untuk menyimpan urutan baru
     */
    public function saveOrder(): void
    {
        try {
            if (empty($this->imageOrder)) {
                throw new \Exception('Urutan gambar tidak valid.');
            }

            // Update urutan setiap gambar
            foreach ($this->imageOrder as $index => $imageId) {
                GalleryImage::where('id', $imageId)
                    ->where('gallery_id', $this->gallery->id)
                    ->update(['urutan' => $index + 1]);
            }

            $this->success(
                title: 'Berhasil!',
                description: 'Urutan gambar berhasil diperbarui.',
                position: 'toast-top toast-end'
            );

            $this->reorderMode = false;
            $this->imageOrder = [];

        } catch (\Exception $e) {
            Log::error('Error saving image order: ' . $e->getMessage(), [
                'gallery_id' => $this->gallery->id,
                'order' => $this->imageOrder
            ]);

            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menyimpan urutan.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk kembali ke gallery edit
     */
    public function back(): void
    {
        $this->redirect(route('admin.galleries.edit', $this->gallery->id), navigate: true);
    }

    /**
     * Computed property untuk mendapatkan daftar gambar
     */
    public function getImagesProperty()
    {
        // Log untuk debugging
        Log::info('Fetching gallery images', [
            'gallery_id' => $this->gallery->id,
            'search' => $this->search,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
            'per_page' => $this->perPage
        ]);
        
        // Pastikan gallery_id valid
        if (!$this->gallery || !$this->gallery->id) {
            Log::error('Invalid gallery ID in getImagesProperty');
            return collect([]);
        }
        
        $query = $this->gallery->images();

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('id', 'like', "%{$this->search}%")
                  ->orWhere('urutan', 'like', "%{$this->search}%")
                  ->orWhere('gambar', 'like', "%{$this->search}%");
            });
        }

        // Apply sorting
        if ($this->sortBy === 'urutan') {
            $query->orderBy('urutan', $this->sortDirection);
        } elseif ($this->sortBy === 'created_at') {
            $query->orderBy('created_at', $this->sortDirection);
        }
        
        // Tingkatkan limit per page untuk menampilkan lebih banyak gambar
        $result = $query->paginate($this->perPage);
        
        // Log hasil query
        Log::info('Gallery images fetched', [
            'count' => $result->count(),
            'total' => $result->total()
        ]);
        
        return $result;
    }

    /**
     * Computed property untuk statistik gambar
     */
    public function getStatsProperty(): array
    {
        return [
            'total' => $this->gallery->images()->count(),
            'total_size' => $this->gallery->images()->count(), // Bisa ditambahkan kalkulasi ukuran file
        ];
    }

    /**
     * Computed property untuk opsi per page
     */
    public function getPerPageOptionsProperty(): array
    {
        return [6, 12, 24, 48];
    }

    /**
     * Computed property untuk opsi sorting
     */
    public function getSortableColumnsProperty(): array
    {
        return [
            'urutan' => 'Urutan',
            'created_at' => 'Tanggal Upload',
        ];
    }

    /**
     * Method untuk reset filter
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->sortBy = 'urutan';
        $this->sortDirection = 'asc';
        $this->perPage = 12;
        $this->resetPage();
    }

    /**
     * Render component
     */
    public function render(): View
    {
        return view('livewire.admin.gallery-images.index', [
            'images' => $this->images,
            'stats' => $this->stats,
            'perPageOptions' => $this->perPageOptions,
            'sortableColumns' => $this->sortableColumns,
        ]);
    }
}