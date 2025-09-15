<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;

/**
 * Livewire Component untuk menampilkan detail fasilitas
 * 
 * Menyediakan tampilan detail lengkap fasilitas dengan:
 * - Informasi lengkap fasilitas
 * - Gambar fasilitas dengan preview
 * - Informasi program studi terkait
 * - Aksi edit dan hapus
 * - Navigasi kembali ke daftar
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Show extends Component
{
    use Toast;

    /**
     * Instance fasilitas yang akan ditampilkan
     */
    public Facility $facility;

    /**
     * ID fasilitas yang akan dihapus (untuk konfirmasi)
     */
    public ?string $facilityToDelete = null;

    /**
     * Mount component dengan data fasilitas
     */
    public function mount(Facility $facility): void
    {
        $this->facility = $facility->load('studyProgram');
    }

    /**
     * Konfirmasi penghapusan fasilitas
     */
    public function confirmDelete(): void
    {
        $this->facilityToDelete = $this->facility->id;
    }

    /**
     * Batalkan penghapusan fasilitas
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
        try {
            // Hapus gambar jika ada
            if ($this->facility->gambar) {
                Storage::disk('public')->delete($this->facility->gambar);
            }

            // Hapus fasilitas dari database
            $this->facility->delete();

            $this->success('Fasilitas berhasil dihapus!');

            // Redirect ke halaman index
            $this->redirect(route('admin.facilities.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error('Gagal menghapus fasilitas: ' . $e->getMessage());
        }
    }

    /**
     * Navigasi ke halaman edit
     */
    public function edit(): void
    {
        $this->redirect(route('admin.facilities.edit', $this->facility), navigate: true);
    }

    /**
     * Navigasi kembali ke daftar fasilitas
     */
    public function backToIndex(): void
    {
        $this->redirect(route('admin.facilities.index'), navigate: true);
    }

    /**
     * Render component
     */
    public function render(): View
    {
        return view('livewire.admin.facilities.show');
    }
}
