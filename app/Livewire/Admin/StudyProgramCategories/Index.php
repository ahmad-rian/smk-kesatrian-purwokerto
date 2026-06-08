<?php

namespace App\Livewire\Admin\StudyProgramCategories;

use App\Models\StudyProgramCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Livewire\Attributes\Layout;

/**
 * Komponen Livewire untuk manajemen kategori program studi di admin
 *
 * Fitur:
 * - Listing kategori dengan pagination
 * - Search dan filter
 * - Delete kategori
 * - Status toggle (aktif/nonaktif)
 * - Urutan kategori
 */
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination, Toast;

    /**
     * Search query untuk filter kategori
     */
    public string $search = '';

    /**
     * Filter status kategori
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
    public ?StudyProgramCategory $categoryToDelete = null;

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
     * Toggle status kategori
     */
    public function toggleStatus(StudyProgramCategory $category): void
    {
        try {
            $category->update([
                'is_active' => !$category->is_active
            ]);

            $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';
            $this->success("Kategori berhasil {$status}!");
        } catch (\Exception $e) {
            $this->error('Gagal mengubah status kategori: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi delete kategori
     */
    public function confirmDelete(StudyProgramCategory $category): void
    {
        $this->categoryToDelete = $category;
        $this->showDeleteModal = true;
    }

    /**
     * Cancel delete
     */
    public function cancelDelete(): void
    {
        $this->categoryToDelete = null;
        $this->showDeleteModal = false;
    }

    /**
     * Delete kategori
     */
    public function deleteCategory(): void
    {
        if (!$this->categoryToDelete) {
            return;
        }

        try {
            // Check if category has study programs
            if ($this->categoryToDelete->study_programs_count > 0) {
                $this->error('Tidak dapat menghapus kategori yang masih memiliki program studi!');
                $this->cancelDelete();
                return;
            }

            $this->categoryToDelete->delete();

            $this->success('Kategori berhasil dihapus!');
            $this->cancelDelete();
        } catch (\Exception $e) {
            $this->error('Gagal menghapus kategori: ' . $e->getMessage());
        }
    }

    /**
     * Get stats kategori
     */
    public function getStatsProperty(): array
    {
        return [
            'total' => StudyProgramCategory::count(),
            'active' => StudyProgramCategory::where('is_active', true)->count(),
            'inactive' => StudyProgramCategory::where('is_active', false)->count(),
        ];
    }

    /**
     * Get categories dengan filter dan search
     */
    public function getCategoriesProperty()
    {
        $query = StudyProgramCategory::query()->withCount('studyPrograms');

        // Apply search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        return $query->ordered()->paginate($this->perPage);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.study-program-categories.index', [
            'categories' => $this->categories,
            'stats' => $this->stats,
        ]);
    }
}
