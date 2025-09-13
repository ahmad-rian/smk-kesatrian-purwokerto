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
class Edit extends Component
{
    use WithFileUploads;

    /**
     * ID carousel
     *
     * @var int
     */
    public int $carouselId;

    /**
     * Judul carousel
     *
     * @var string
     */
    public string $judul = '';

    /**
     * File gambar carousel baru
     *
     * @var mixed
     */
    public $gambar;

    /**
     * Path gambar carousel saat ini
     *
     * @var string|null
     */
    public ?string $gambarPath = null;

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
     * Mount komponen
     *
     * @param HomeCarousel $homeCarousel
     * @return void
     */
    public function mount(HomeCarousel $homeCarousel): void
    {
        $this->carouselId = (int) $homeCarousel->id;

        $this->judul = $homeCarousel->judul;
        $this->gambarPath = $homeCarousel->gambar;
        $this->aktif = $homeCarousel->aktif;
        $this->urutan = $homeCarousel->urutan;
    }

    /**
     * Aturan validasi
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'gambar' => 'nullable|image|max:2048', // max 2MB
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
            'gambar.image' => 'File harus berupa gambar',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
            'urutan.required' => 'Urutan harus diisi',
            'urutan.integer' => 'Urutan harus berupa angka',
            'urutan.min' => 'Urutan minimal 1',
        ];
    }

    /**
     * Update carousel
     *
     * @return mixed
     */
    public function update()
    {
        $this->validate();

        try {
            $carousel = HomeCarousel::findOrFail($this->carouselId);
            $data = [
                'judul' => $this->judul,
                'aktif' => $this->aktif,
                'urutan' => $this->urutan,
            ];

            // Upload gambar baru jika ada
            if ($this->gambar) {
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
                    throw new \Exception('Gagal mengkonversi dan menyimpan gambar baru');
                }

                // Hapus gambar lama jika ada dan upload berhasil
                if ($carousel->gambar && Storage::disk('public')->exists($carousel->gambar)) {
                    $imageService->deleteOldImage($carousel->gambar);
                }

                $data['gambar'] = $gambarPath;
                $this->gambarPath = $gambarPath;
            }

            // Update carousel
            $carousel->update($data);

            // Flash message
            session()->flash('message', 'Carousel berhasil diperbarui');

            // Emit event
            $this->dispatch('carouselUpdated');

            // Redirect ke halaman index
            return $this->redirect(route('admin.home-carousels.index'), navigate: true);
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error saat mengupdate carousel: ' . $e->getMessage());

            // Tampilkan pesan error ke user
            session()->flash('error', 'Gagal mengupdate carousel: ' . $e->getMessage());
        }
    }

    /**
     * Render komponen
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.admin.home-carousels.edit');
    }
}
