<?php

namespace App\Livewire\Frontend\Fasilitas;

use App\Models\Facility;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Livewire Component untuk halaman detail fasilitas
 * 
 * Menampilkan informasi lengkap fasilitas termasuk:
 * - Gallery gambar dengan carousel
 * - Informasi detail fasilitas
 * - Program studi terkait
 * - Navigasi ke fasilitas lain
 * 
 * @package App\Livewire\Frontend\Fasilitas
 * @author Laravel Expert Agent
 */
#[Layout('components.layouts.frontend')]
#[Title('Detail Fasilitas - SMK Kesatrian')]
class Detail extends Component
{
    /**
     * Instance fasilitas yang sedang ditampilkan
     * 
     * @var Facility|null
     */
    public ?Facility $facility = null;

    /**
     * Slug atau ID fasilitas untuk URL
     * 
     * @var string
     */
    public string $slug;

    /**
     * Daftar fasilitas terkait (same category)
     * 
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $relatedFacilities;

    /**
     * Mount component dengan slug atau ID fasilitas
     * 
     * @param string $slug Slug atau ID fasilitas
     * @return void
     * @throws ModelNotFoundException
     */
    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->loadFacility();
        $this->loadRelatedFacilities();
    }

    /**
     * Load data fasilitas berdasarkan ID
     * 
     * @return void
     */
    private function loadFacility(): void
    {
        // Cari berdasarkan ID saja karena tabel facilities tidak memiliki kolom slug
        $this->facility = Facility::with(['studyProgram', 'images'])
            ->where('id', $this->slug)
            ->where('aktif', true)
            ->firstOrFail();
    }

    /**
     * Load fasilitas terkait (kategori sama, exclude current)
     * 
     * @return void
     */
    private function loadRelatedFacilities(): void
    {
        $this->relatedFacilities = Facility::with(['studyProgram', 'images'])
            ->where('kategori', $this->facility->kategori)
            ->where('id', '!=', $this->facility->id)
            ->where('aktif', true)
            ->limit(4)
            ->get();
    }

    /**
     * Navigate ke fasilitas lain
     * 
     * @param string $id ID fasilitas tujuan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function goToFacility(string $id)
    {
        return redirect()->route('fasilitas.detail', ['slug' => $id]);
    }

    /**
     * Kembali ke halaman index fasilitas
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function backToIndex()
    {
        return redirect()->route('fasilitas.index');
    }

    /**
     * Render component
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.frontend.fasilitas.detail');
    }
}
