<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use App\Models\StudyProgram;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

/**
 * Livewire Component untuk menampilkan daftar fasilitas
 * 
 * Menyediakan fitur:
 * - Tampilan daftar fasilitas dengan pagination
 * - Pencarian berdasarkan nama dan deskripsi
 * - Filter berdasarkan program studi
 * - Aksi CRUD (Create, Read, Update, Delete)
 * - Konfirmasi penghapusan dengan modal
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use Toast, WithPagination;

    /**
     * Kata kunci pencarian
     */
    #[Url(as: 'search')]
    public string $search = '';

    /**
     * Filter berdasarkan program studi
     */
    #[Url(as: 'program')]
    public string $study_program_filter = '';

    /**
     * Jumlah item per halaman
     */
    #[Url(as: 'per_page')]
    public int $perPage = 10;

    /**
     * Urutan sorting
     */
    #[Url(as: 'sort')]
    public string $sortBy = 'created_at';

    /**
     * Arah sorting
     */
    #[Url(as: 'direction')]
    public string $sortDirection = 'desc';

    /**
     * ID fasilitas yang akan dihapus
     */
    public ?string $facilityToDelete = null;

    /**
     * Daftar opsi jumlah item per halaman
     */
    public array $perPageOptions = [5, 10, 25, 50];

    /**
     * Daftar kolom yang bisa diurutkan
     */
    public array $sortableColumns = [
        'nama' => 'Nama Fasilitas',
        'study_program_id' => 'Program Studi',
        'created_at' => 'Tanggal Dibuat',
        'updated_at' => 'Terakhir Diupdate'
    ];

    /**
     * Reset pagination saat search berubah
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat filter berubah
     */
    public function updatedStudyProgramFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat per page berubah
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Method untuk mengubah urutan sorting
     */
    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    /**
     * Method untuk reset semua filter
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->study_program_filter = '';
        $this->perPage = 10;
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
        
        $this->success('Filter berhasil direset');
    }

    /**
     * Konfirmasi penghapusan fasilitas
     */
    public function confirmDelete(string $facilityId): void
    {
        $this->facilityToDelete = $facilityId;
    }

    /**
     * Batalkan penghapusan
     */
    public function cancelDelete(): void
    {
        $this->facilityToDelete = null;
    }

    /**
     * Hapus fasilitas
     */
    public function deleteFacility(): void
    {
        if (!$this->facilityToDelete) {
            $this->error('Fasilitas tidak ditemukan');
            return;
        }

        try {
            $facility = Facility::findOrFail($this->facilityToDelete);
            
            // Hapus gambar jika ada
            if ($facility->gambar) {
                $facility->deleteImage();
            }
            
            $facilityName = $facility->nama;
            $facility->delete();
            
            $this->facilityToDelete = null;
            $this->success("Fasilitas '{$facilityName}' berhasil dihapus");
            
        } catch (\Exception $e) {
            $this->error('Gagal menghapus fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Get daftar fasilitas dengan filter dan pagination
     */
    public function getFacilitiesProperty()
    {
        return Facility::query()
            ->with(['studyProgram', 'images', 'primaryImage'])
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->study_program_filter, function (Builder $query) {
                $query->where('study_program_id', $this->study_program_filter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    /**
     * Get daftar program studi untuk filter
     */
    public function getStudyProgramsProperty()
    {
        return StudyProgram::orderBy('nama')->get();
    }

    /**
     * Get statistik fasilitas
     */
    public function getStatsProperty(): array
    {
        return [
            'total' => Facility::count(),
            'with_image' => Facility::whereNotNull('gambar')->count(),
            'without_image' => Facility::whereNull('gambar')->count(),
            'by_program' => Facility::with('studyProgram')
                ->get()
                ->groupBy('studyProgram.nama')
                ->map->count()
                ->toArray()
        ];
    }

    /**
     * Render component
     */
    public function render(): View
    {
        return view('livewire.admin.facilities.index', [
            'facilities' => $this->facilities,
            'studyPrograms' => $this->studyPrograms,
            'stats' => $this->stats
        ]);
    }
}