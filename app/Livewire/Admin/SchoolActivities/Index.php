<?php

namespace App\Livewire\Admin\SchoolActivities;

use App\Models\SchoolActivity;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;
use Livewire\Attributes\Layout;
use App\Services\ImageConversionService;

/**
 * Livewire Component untuk menampilkan daftar Kegiatan Sekolah
 * 
 * Fitur:
 * - Pagination dengan 10 item per halaman
 * - Pencarian berdasarkan nama kegiatan, kategori, lokasi, dan penanggung jawab
 * - Filter berdasarkan status aktif dan unggulan
 * - Filter berdasarkan kategori kegiatan
 * - Sorting berdasarkan tanggal mulai dan nama kegiatan
 * - Toast notification untuk feedback
 * - Konfirmasi hapus dengan modal
 * - Toggle status aktif dan unggulan
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
     * Property untuk filter unggulan
     */
    public string $unggulanFilter = 'all';

    /**
     * Property untuk filter kategori
     */
    public string $kategoriFilter = 'all';

    /**
     * Property untuk sorting
     */
    public string $sortBy = 'tanggal_mulai';
    public string $sortDirection = 'desc';

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
     * Reset pagination saat filter unggulan berubah
     */
    public function updatingUnggulanFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination saat filter kategori berubah
     */
    public function updatingKategoriFilter(): void
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
     * Method untuk menghapus kegiatan sekolah
     */
    public function delete(): void
    {
        if (!$this->deleteId) {
            return;
        }

        try {
            $schoolActivity = SchoolActivity::findOrFail($this->deleteId);
            $nama = $schoolActivity->nama_kegiatan;
            
            // Hapus file gambar dari storage jika ada
            if ($schoolActivity->gambar_utama) {
                $imageService = app(ImageConversionService::class);
                $imageService->deleteOldImage($schoolActivity->gambar_utama);
            }

            // Hapus record dari database
            $schoolActivity->delete();

            $this->success(
                title: 'Berhasil!',
                description: "Kegiatan '{$nama}' berhasil dihapus.",
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menghapus kegiatan.',
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
            $schoolActivity = SchoolActivity::findOrFail($id);
            $schoolActivity->update(['aktif' => !$schoolActivity->aktif]);

            $status = $schoolActivity->aktif ? 'diaktifkan' : 'dinonaktifkan';

            $this->success(
                title: 'Berhasil!',
                description: "Kegiatan '{$schoolActivity->nama_kegiatan}' berhasil {$status}.",
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat mengubah status kegiatan.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk toggle status unggulan
     */
    public function toggleUnggulan(string $id): void
    {
        try {
            $schoolActivity = SchoolActivity::findOrFail($id);
            $schoolActivity->update(['unggulan' => !$schoolActivity->unggulan]);

            $status = $schoolActivity->unggulan ? 'dijadikan unggulan' : 'dihapus dari unggulan';

            $this->success(
                title: 'Berhasil!',
                description: "Kegiatan '{$schoolActivity->nama_kegiatan}' berhasil {$status}.",
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat mengubah status unggulan.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk mendapatkan data kegiatan sekolah dengan filter dan pencarian
     */
    public function getSchoolActivitiesProperty()
    {
        $query = SchoolActivity::query()->with('creator');

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

        // Apply unggulan filter
        if ($this->unggulanFilter === 'yes') {
            $query->where('unggulan', true);
        } elseif ($this->unggulanFilter === 'no') {
            $query->where('unggulan', false);
        }

        // Apply kategori filter
        if ($this->kategoriFilter !== 'all') {
            $query->where('kategori', $this->kategoriFilter);
        }

        // Apply sorting
        if ($this->sortBy === 'tanggal_mulai') {
            $query->orderBy('tanggal_mulai', $this->sortDirection)
                ->orderBy('nama_kegiatan', 'asc');
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
            'total' => SchoolActivity::count(),
            'active' => SchoolActivity::where('aktif', true)->count(),
            'inactive' => SchoolActivity::where('aktif', false)->count(),
            'unggulan' => SchoolActivity::where('unggulan', true)->count(),
            'akan_datang' => SchoolActivity::where('tanggal_mulai', '>', now())->count(),
            'berlangsung' => SchoolActivity::where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())->count(),
            'selesai' => SchoolActivity::where('tanggal_selesai', '<', now())->count(),
        ];
    }

    /**
     * Method untuk mendapatkan opsi kategori
     */
    public function getKategoriOptionsProperty(): array
    {
        return SchoolActivity::getKategoriOptions();
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.school-activities.index', [
            'schoolActivities' => $this->schoolActivities,
            'stats' => $this->stats,
            'kategoriOptions' => $this->kategoriOptions,
        ]);
    }
}