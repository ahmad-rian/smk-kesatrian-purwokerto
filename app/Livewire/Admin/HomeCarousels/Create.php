<?php

namespace App\Livewire\Admin\HomeCarousels;

use App\Models\HomeCarousel;
use App\Services\ImageConversionService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

#[Layout('livewire.admin.layout')]
class Create extends Component
{
    use WithFileUploads;

    /**
     * Judul carousel
     *
     * @var string
     */
    public string $judul = '';

    /**
     * File gambar carousel
     *
     * @var mixed
     */
    public $gambar;

    /**
     * Status aktif carousel
     *
     * @var bool
     */
    public bool $aktif = true;

    /**
     * Urutan carousel
     *
     * @var int
     */
    public int $urutan = 1;

    /**
     * Aturan validasi
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'gambar' => 'required|image|max:2048', // max 2MB
            'aktif' => 'boolean',
            'urutan' => 'required|integer|min:1',
        ];
    }

    /**
     * Pesan validasi
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            'judul.required' => 'Judul carousel harus diisi',
            'judul.max' => 'Judul carousel maksimal 255 karakter',
            'gambar.required' => 'Gambar carousel harus diupload',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
            'urutan.required' => 'Urutan harus diisi',
            'urutan.integer' => 'Urutan harus berupa angka',
            'urutan.min' => 'Urutan minimal 1',
        ];
    }

    /**
     * Simpan carousel
     *
     * @return mixed
     */
    public function save()
    {
        $this->validate();

        try {
            // Konversi gambar ke WebP menggunakan ImageConversionService
            $imageService = new ImageConversionService();

            // Validasi gambar
            if (!$imageService->isValidImage($this->gambar)) {
                throw new \Exception('File yang diupload bukan gambar yang valid');
            }

            // Konversi ke WebP dengan kualitas tinggi untuk carousel
            $gambarPath = $imageService->convertToWebP(
                $this->gambar,
                'carousel',
                ['quality' => 90, 'maxWidth' => 1920, 'maxHeight' => 800]
            );

            // Verifikasi file berhasil dikonversi dan disimpan
            if (!$gambarPath || !Storage::disk('public')->exists($gambarPath)) {
                throw new \Exception('Gagal mengkonversi dan menyimpan gambar');
            }

            // Simpan carousel
            HomeCarousel::create([
                'judul' => $this->judul,
                'gambar' => $gambarPath,
                'aktif' => $this->aktif,
                'urutan' => $this->urutan,
            ]);

            // Reset form
            $this->reset(['judul', 'gambar', 'aktif', 'urutan']);
            $this->urutan = 1;
            $this->aktif = true;

            // Flash message
            session()->flash('message', 'Carousel berhasil ditambahkan');

            // Emit event
            $this->dispatch('carouselCreated');

            // Redirect ke halaman index
            return $this->redirect(route('admin.home-carousels.index'), navigate: true);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error saat menyimpan carousel: ' . $e->getMessage());

            // Tampilkan pesan error ke user
            session()->flash('error', 'Gagal menyimpan carousel: ' . $e->getMessage());
        }
    }

    /**
     * Render komponen
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.home-carousels.create');
    }
}
