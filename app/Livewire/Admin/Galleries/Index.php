<?php

namespace App\Livewire\Admin\Galleries;

use App\Models\Gallery;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\ImageConversionService;

/**
 * Livewire Component untuk halaman index Gallery
 * 
 * Menampilkan daftar gallery dengan fitur:
 * - Pagination
 * - Pencarian berdasarkan judul dan deskripsi
 * - Filter berdasarkan status aktif/nonaktif
 * - Toggle status aktif/nonaktif
 * - Hapus gallery dengan konfirmasi
 * - Sorting berdasarkan urutan
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination, Toast;

    /**
     * Properties untuk pencarian dan filter
     */
    public string $search = '';
    public string $statusFilter = 'semua';
    public int $perPage = 10;

    /**
     * Properties untuk modal delete
     */
    public bool $showDeleteModal = false;
    public ?string $deleteId = null;
    public ?string $deleteGalleryName = null;

    /**
     * Properties untuk sorting
     */
    public string $sortBy = 'urutan';
    public string $sortDirection = 'asc';

    /**
     * Reset pagination saat search berubah
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat filter berubah
     */
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat per page berubah
     */
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Method untuk sorting
     */
    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    /**
     * Method untuk toggle status aktif/nonaktif
     */
    public function toggleStatus(string $id): void
    {
        try {
            $gallery = Gallery::findOrFail($id);
            $gallery->update(['aktif' => !$gallery->aktif]);

            $status = $gallery->aktif ? 'diaktifkan' : 'dinonaktifkan';
            $this->success(
                title: 'Berhasil!',
                description: "Gallery '{$gallery->judul}' berhasil {$status}.",
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            Log::error('Error toggle gallery status: ' . $e->getMessage());
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat mengubah status gallery.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk menampilkan modal konfirmasi hapus
     */
    public function confirmDelete(string $id): void
    {
        $gallery = Gallery::findOrFail($id);
        $this->deleteId = $id;
        $this->deleteGalleryName = $gallery->judul;
        $this->showDeleteModal = true;
    }

    /**
     * Method untuk membatalkan hapus
     */
    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->deleteGalleryName = null;
        $this->showDeleteModal = false;
    }

    /**
     * Method untuk menghapus gallery
     */
    public function delete(): void
    {
        try {
            if (!$this->deleteId) {
                throw new \Exception('ID gallery tidak valid.');
            }

            $gallery = Gallery::findOrFail($this->deleteId);
            $galleryName = $gallery->judul;

            // Hapus gallery (akan otomatis hapus gambar melalui boot method)
            $gallery->delete();

            $this->success(
                title: 'Berhasil!',
                description: "Gallery '{$galleryName}' berhasil dihapus.",
                position: 'toast-top toast-end'
            );

            $this->cancelDelete();
        } catch (\Exception $e) {
            Log::error('Error deleting gallery: ' . $e->getMessage());
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menghapus gallery.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk reset filter
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->statusFilter = 'semua';
        $this->sortBy = 'urutan';
        $this->sortDirection = 'asc';
        $this->resetPage();
    }

    /**
     * Computed property untuk mendapatkan data galleries
     */
    public function getGalleriesProperty()
    {
        $query = Gallery::query();
        
        // Tambahkan withCount untuk menghitung jumlah gambar
        $query->withCount('images');

        // Apply search filter
        if (!empty($this->search)) {
            $query->search($this->search);
        }

        // Apply status filter
        if ($this->statusFilter !== 'semua') {
            $query->byStatus($this->statusFilter);
        }

        // Apply sorting
        if ($this->sortBy === 'urutan') {
            $query->orderBy('urutan', $this->sortDirection)
                  ->orderBy('judul', 'asc');
        } elseif ($this->sortBy === 'judul') {
            $query->orderBy('judul', $this->sortDirection);
        } elseif ($this->sortBy === 'created_at') {
            $query->orderBy('created_at', $this->sortDirection);
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate($this->perPage);
    }

    /**
     * Computed property untuk statistik
     */
    public function getStatsProperty(): array
    {
        return [
            'total' => Gallery::count(),
            'aktif' => Gallery::where('aktif', true)->count(),
            'nonaktif' => Gallery::where('aktif', false)->count(),
            'total_images' => \App\Models\GalleryImage::count(),
        ];
    }
    
    /**
     * Computed property untuk total galleries
     */
    public function getTotalGalleriesProperty(): int
    {
        return $this->stats['total'];
    }
    
    /**
     * Computed property untuk galleries aktif
     */
    public function getActiveGalleriesProperty(): int
    {
        return $this->stats['aktif'];
    }
    
    /**
     * Computed property untuk galleries nonaktif
     */
    public function getInactiveGalleriesProperty(): int
    {
        return $this->stats['nonaktif'];
    }
    
    /**
     * Computed property untuk total images
     */
    public function getTotalImagesProperty(): int
    {
        return $this->stats['total_images'];
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.galleries.index', [
            'galleries' => $this->galleries,
            'stats' => $this->stats,
        ]);
    }
}