<?php

namespace App\Livewire\Admin\StudyPrograms;

use App\Models\StudyProgram;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Livewire\Attributes\Layout;

/**
 * Livewire Component untuk menampilkan daftar Program Studi
 * 
 * Fitur:
 * - Pagination dengan 10 item per halaman
 * - Pencarian berdasarkan kode, nama, dan ketua program
 * - Filter berdasarkan status aktif
 * - Sorting berdasarkan urutan dan nama
 * - Toast notification untuk feedback
 * - Konfirmasi hapus dengan modal
 */
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination, Toast;

    /**
     * Property untuk pencarian
     */
    public string $search = '';

    /**
     * Property untuk filter status aktif
     */
    public string $statusFilter = 'all';

    /**
     * Property untuk sorting
     */
    public string $sortBy = 'urutan';
    public string $sortDirection = 'asc';

    /**
     * Property untuk konfirmasi hapus
     */
    public ?string $deleteId = null;
    public bool $showDeleteModal = false;

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
     * Method untuk mengubah sorting
     */
    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Method untuk menampilkan modal konfirmasi hapus
     */
    public function confirmDelete(string $id): void
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    /**
     * Method untuk membatalkan hapus
     */
    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->showDeleteModal = false;
    }

    /**
     * Method untuk menghapus program studi
     */
    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        try {
            $studyProgram = StudyProgram::findOrFail($this->deleteId);
            $nama = $studyProgram->nama;

            $studyProgram->delete();

            $this->success(
                title: 'Berhasil!',
                description: "Program studi '{$nama}' berhasil dihapus.",
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menghapus program studi.',
                position: 'toast-top toast-end'
            );
        } finally {
            $this->cancelDelete();
        }
    }

    /**
     * Method untuk toggle status aktif
     */
    public function toggleStatus(string $id): void
    {
        try {
            $studyProgram = StudyProgram::findOrFail($id);
            $studyProgram->update(['aktif' => !$studyProgram->aktif]);

            $status = $studyProgram->aktif ? 'diaktifkan' : 'dinonaktifkan';

            $this->success(
                title: 'Berhasil!',
                description: "Program studi '{$studyProgram->nama}' berhasil {$status}.",
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat mengubah status program studi.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk mendapatkan data program studi dengan filter dan pencarian
     */
    public function getStudyProgramsProperty()
    {
        $query = StudyProgram::query();

        // Apply search filter
        if ($this->search) {
            $query->search($this->search);
        }

        // Apply status filter
        if ($this->statusFilter === 'active') {
            $query->where('aktif', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('aktif', false);
        }

        // Apply sorting
        if ($this->sortBy === 'urutan') {
            $query->orderBy('urutan', $this->sortDirection)
                ->orderBy('nama', 'asc');
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        return $query->paginate(10);
    }

    /**
     * Method untuk mendapatkan statistik
     */
    public function getStatsProperty(): array
    {
        return [
            'total' => StudyProgram::count(),
            'active' => StudyProgram::where('aktif', true)->count(),
            'inactive' => StudyProgram::where('aktif', false)->count(),
        ];
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.study-programs.index', [
            'studyPrograms' => $this->studyPrograms,
            'stats' => $this->stats,
        ]);
    }
}
