<?php

namespace App\Livewire\Admin\HomeCarousels;

use App\Models\HomeCarousel;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination;

    /**
     * Kata kunci pencarian
     *
     * @var string
     */
    public string $search = '';

    /**
     * Jumlah item per halaman
     *
     * @var int
     */
    public int $perPage = 10;

    /**
     * Listener untuk event yang dipancarkan
     *
     * @var array
     */
    protected $listeners = [
        'carouselCreated' => '$refresh',
        'carouselUpdated' => '$refresh',
    ];

    /**
     * Reset pagination ketika pencarian berubah
     *
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Hapus carousel
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id): void
    {
        $carousel = HomeCarousel::findOrFail($id);

        // Hapus file gambar jika ada
        if ($carousel->gambar && Storage::exists($carousel->gambar)) {
            Storage::delete($carousel->gambar);
        }

        $carousel->delete();

        session()->flash('message', 'Carousel berhasil dihapus');
    }

    /**
     * Toggle status aktif carousel
     *
     * @param string $id
     * @return void
     */
    public function toggleActive(string $id): void
    {
        $carousel = HomeCarousel::findOrFail($id);
        $carousel->aktif = !$carousel->aktif;
        $carousel->save();

        session()->flash('message', 'Status carousel berhasil diubah');
    }

    /**
     * Render komponen
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        $carousels = HomeCarousel::where('judul', 'like', '%' . $this->search . '%')
            ->orderBy('urutan')
            ->paginate($this->perPage);

        return view('livewire.admin.home-carousels.index', [
            'carousels' => $carousels,
        ]);
    }
}