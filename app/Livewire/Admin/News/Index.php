<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

/**
 * Komponen Livewire untuk manajemen berita di admin
 * 
 * Fitur:
 * - Listing berita dengan pagination
 * - Search dan filter
 * - Delete berita
 * - Status toggle (aktif/nonaktif)
 */
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination, Toast;

    /**
     * Search query untuk filter berita
     */
    public string $search = '';

    /**
     * Filter status berita
     */
    public string $statusFilter = 'all';

    /**
     * Jumlah item per halaman
     */
    public int $perPage = 10;

    /**
     * Konfirmasi delete
     */
    public bool $showDeleteModal = false;
    public ?News $newsToDelete = null;

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
     * Konfirmasi delete berita
     */
    public function confirmDelete(News $news): void
    {
        $this->newsToDelete = $news;
        $this->showDeleteModal = true;
    }

    /**
     * Cancel delete
     */
    public function cancelDelete(): void
    {
        $this->newsToDelete = null;
        $this->showDeleteModal = false;
    }

    /**
     * Delete berita
     */
    public function deleteNews(): void
    {
        if (!$this->newsToDelete) {
            return;
        }

        try {
            // Hapus gambar jika ada
            if ($this->newsToDelete->gambar) {
                Storage::disk('public')->delete($this->newsToDelete->gambar);
            }

            $this->newsToDelete->delete();
            
            $this->success('Berita berhasil dihapus!');
            $this->cancelDelete();
            
        } catch (\Exception $e) {
            $this->error('Gagal menghapus berita: ' . $e->getMessage());
        }
    }

    /**
     * Toggle status berita
     */
    public function toggleStatus(News $news): void
    {
        try {
            $newStatus = $news->status === 'published' ? 'draft' : 'published';
            $news->update(['status' => $newStatus]);
            
            $statusText = $newStatus === 'published' ? 'diaktifkan' : 'dinonaktifkan';
            $this->success("Berita berhasil {$statusText}!");
            
        } catch (\Exception $e) {
            $this->error('Gagal mengubah status: ' . $e->getMessage());
        }
    }

    /**
     * Get berita dengan filter dan pagination
     */
    public function getNewsProperty()
    {
        $query = News::query()
            ->when($this->search, function ($query) {
                $query->where('judul', 'like', '%' . $this->search . '%')
                      ->orWhere('konten', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $dbStatus = $this->statusFilter === 'aktif' ? 'published' : 'draft';
                $query->where('status', $dbStatus);
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate($this->perPage);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.news.index', [
            'newsList' => $this->news
        ]);
    }
}