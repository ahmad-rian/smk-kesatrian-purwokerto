<?php

namespace App\Livewire\Admin\Galleries;

use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Services\ImageConversionService;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

/**
 * Livewire Component untuk edit Gallery
 * 
 * Fitur yang tersedia:
 * - Edit data gallery
 * - Upload/ganti gambar sampul
 * - Kelola gambar dalam gallery (upload multiple, hapus, reorder)
 * - Preview gambar
 * - Validasi input yang komprehensif
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use WithFileUploads, Toast;

    /**
     * Model instance
     */
    public Gallery $gallery;

    /**
     * Properties untuk form input
     */
    public string $judul = '';
    public string $slug = '';
    public string $deskripsi = '';
    public bool $aktif = true;
    public int $urutan = 1;

    /**
     * Properties untuk upload gambar
     */
    public $gambar_sampul;
    public $new_images = [];
    public ?string $currentGambarSampul = null;

    /**
     * Properties untuk UI state
     */
    public bool $isLoading = false;
    public bool $autoGenerateSlug = false;
    public string $activeTab = 'info';

    /**
     * Properties untuk modal delete image
     */
    public bool $showDeleteImageModal = false;
    public ?string $deleteImageId = null;

    /**
     * Service dependencies
     */
    private ImageConversionService $imageService;

    /**
     * Boot component dengan dependency injection
     */
    public function boot(ImageConversionService $imageService): void
    {
        $this->imageService = $imageService;
    }

    /**
     * Mount component dengan data gallery
     */
    public function mount(Gallery $gallery): void
    {
        $this->gallery = $gallery;
        $this->loadCurrentData();
    }

    /**
     * Load data gallery ke form
     */
    private function loadCurrentData(): void
    {
        $this->judul = $this->gallery->judul;
        $this->slug = $this->gallery->slug;
        $this->deskripsi = $this->gallery->deskripsi ?? '';
        $this->aktif = $this->gallery->aktif;
        $this->urutan = $this->gallery->urutan;
        $this->currentGambarSampul = $this->gallery->gambar_sampul;
    }

    /**
     * Auto-generate slug saat judul berubah
     */
    public function updatedJudul(): void
    {
        if ($this->autoGenerateSlug && !empty($this->judul)) {
            $this->slug = Str::slug($this->judul);
        }
    }

    /**
     * Disable auto-generate slug saat user edit manual
     */
    public function updatedSlug(): void
    {
        $this->autoGenerateSlug = false;
    }

    /**
     * Method untuk enable kembali auto-generate slug
     */
    public function enableAutoSlug(): void
    {
        $this->autoGenerateSlug = true;
        $this->updatedJudul();
    }

    /**
     * Validasi rules untuk form
     */
    protected function rules(): array
    {
        return Gallery::validationRules($this->gallery->id);
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return Gallery::validationMessages();
    }

    /**
     * Real-time validation
     */
    public function updated($propertyName): void
    {
        if (in_array($propertyName, ['judul', 'slug', 'deskripsi', 'urutan'])) {
            $this->validateOnly($propertyName);
        }
    }

    /**
     * Method untuk update gallery
     */
    public function update(): void
    {
        $this->isLoading = true;

        try {
            // Validasi input
            $validatedData = $this->validate();

            DB::beginTransaction();

            // Siapkan data untuk update
            $data = [
                'judul' => $this->judul,
                'slug' => $this->slug,
                'deskripsi' => $this->deskripsi ?: null,
                'aktif' => $this->aktif,
                'urutan' => $this->urutan,
            ];

            // Handle upload gambar sampul baru jika ada
            if ($this->gambar_sampul) {
                // Hapus gambar lama jika ada
                if ($this->currentGambarSampul) {
                    $this->imageService->deleteOldImage($this->currentGambarSampul);
                }

                $data['gambar_sampul'] = $this->imageService->convertToWebP(
                    $this->gambar_sampul,
                    'galleries/sampul'
                );
                $this->currentGambarSampul = $data['gambar_sampul'];
                $this->gambar_sampul = null;
            }

            // Update gallery
            $this->gallery->update($data);

            DB::commit();

            $this->success(
                title: 'Berhasil!',
                description: "Gallery '{$this->gallery->judul}' berhasil diperbarui.",
                position: 'toast-top toast-end'
            );

            // Refresh data
            $this->gallery->refresh();
            $this->loadCurrentData();

        } catch (ValidationException $e) {
            $this->error(
                title: 'Validasi Gagal!',
                description: 'Silakan periksa kembali data yang diinput.',
                position: 'toast-top toast-end'
            );
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating gallery: ' . $e->getMessage(), [
                'gallery_id' => $this->gallery->id,
                'data' => $this->all(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat memperbarui gallery.',
                position: 'toast-top toast-end'
            );
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Method untuk mendapatkan preview gambar yang akan diupload
     */
    public function getNewImagesPreviewProperty(): array
    {
        $previews = [];
        
        if (!empty($this->new_images)) {
            foreach ($this->new_images as $index => $image) {
                if ($image instanceof UploadedFile) {
                    try {
                        $previews[] = [
                            'url' => $image->temporaryUrl(),
                            'name' => $image->getClientOriginalName(),
                            'size' => $image->getSize(),
                            'type' => $image->getMimeType(),
                        ];
                    } catch (\Exception $e) {
                        Log::error("Error creating preview for image {$index}: {$e->getMessage()}");
                    }
                }
            }
        }
        
        return $previews;
    }
    
    /**
     * Method untuk upload gambar baru ke gallery
     */
    public function uploadImages(): void
    {
        Log::debug('Memulai proses upload gambar');
        
        if (empty($this->new_images)) {
            $this->error(
                title: 'Peringatan!',
                description: 'Silakan pilih gambar yang akan diupload.',
                position: 'toast-top toast-end'
            );
            Log::debug('Tidak ada gambar yang dipilih untuk diupload');
            return;
        }
        
        Log::debug('Jumlah gambar yang akan diupload: ' . count($this->new_images));

        try {
            DB::beginTransaction();
            Log::debug('Memulai transaksi database untuk upload gambar');

            $uploadedCount = 0;
            $nextUrutan = GalleryImage::getNextUrutan($this->gallery->id);

            foreach ($this->new_images as $index => $image) {
                if ($image instanceof UploadedFile) {
                    // Log untuk debugging
                    Log::debug("Processing image {$index}: {$image->getClientOriginalName()}, size: {$image->getSize()}, type: {$image->getMimeType()}");
                    
                    $imagePath = $this->imageService->convertToWebP(
                        $image,
                        'galleries/images'
                    );
                    
                    // Log path hasil konversi
                    Log::debug("Image {$index} converted to WebP: {$imagePath}");

                    // Pastikan gallery_id valid
                    if (!$this->gallery || !$this->gallery->id) {
                        throw new \Exception('Gallery ID tidak valid');
                    }

                    // Buat record gambar baru
                    $galleryImage = GalleryImage::create([
                        'gallery_id' => $this->gallery->id,
                        'gambar' => $imagePath,
                        'urutan' => $nextUrutan++,
                    ]);
                    
                    // Log hasil pembuatan record
                    Log::debug("Gallery image record created: ID={$galleryImage->id}, urutan={$galleryImage->urutan}");

                    $uploadedCount++;
                }
            }

            DB::commit();
            Log::debug("Transaksi database berhasil, {$uploadedCount} gambar diupload");

            // Refresh data gallery
            $this->gallery->refresh();
            Log::debug('Data gallery direfresh setelah upload gambar');

            $this->success(
                title: 'Berhasil!',
                description: "{$uploadedCount} gambar berhasil diupload.",
                position: 'toast-top toast-end'
            );

            $this->new_images = [];
            $this->activeTab = 'images';

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error uploading gallery images: ' . $e->getMessage(), [
                'gallery_id' => $this->gallery->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat mengupload gambar: ' . $e->getMessage(),
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk konfirmasi hapus gambar
     */
    public function confirmDeleteImage(string $imageId): void
    {
        $this->deleteImageId = $imageId;
        $this->showDeleteImageModal = true;
    }

    /**
     * Method untuk membatalkan hapus gambar
     */
    public function cancelDeleteImage(): void
    {
        $this->deleteImageId = null;
        $this->showDeleteImageModal = false;
    }

    /**
     * Method untuk menghapus gambar dari gallery
     */
    public function deleteImage(): void
    {
        try {
            if (!$this->deleteImageId) {
                throw new \Exception('ID gambar tidak valid.');
            }

            $image = GalleryImage::findOrFail($this->deleteImageId);
            
            // Hapus file gambar dari storage
            if ($image->gambar) {
                $this->imageService->deleteOldImage($image->gambar);
            }

            // Hapus record dari database
            $image->delete();

            $this->success(
                title: 'Berhasil!',
                description: 'Gambar berhasil dihapus.',
                position: 'toast-top toast-end'
            );

            $this->cancelDeleteImage();

        } catch (\Exception $e) {
            Log::error('Error deleting gallery image: ' . $e->getMessage());
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menghapus gambar.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk menghapus gambar sampul saat ini
     */
    public function removeCurrentGambarSampul(): void
    {
        try {
            if ($this->currentGambarSampul) {
                // Hapus file gambar dari storage
                $this->imageService->deleteOldImage($this->currentGambarSampul);
                
                // Update database
                $this->gallery->update(['gambar_sampul' => null]);
                $this->currentGambarSampul = null;

                $this->success(
                    title: 'Berhasil!',
                    description: 'Gambar sampul berhasil dihapus.',
                    position: 'toast-top toast-end'
                );
            }
        } catch (\Exception $e) {
            Log::error('Error removing gallery cover image: ' . $e->getMessage());
            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menghapus gambar sampul.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Method untuk preview gambar sampul baru
     */
    public function getImagePreviewProperty(): ?string
    {
        return $this->gambar_sampul ? $this->gambar_sampul->temporaryUrl() : null;
    }

    /**
     * Method untuk URL gambar sampul saat ini
     */
    public function getCurrentGambarSampulUrlProperty(): ?string
    {
        return $this->currentGambarSampul ? Storage::url($this->currentGambarSampul) : null;
    }

    /**
     * Method untuk menghapus gambar sampul yang diupload
     */
    public function removeImage(): void
    {
        $this->gambar_sampul = null;
    }

    /**
     * Method untuk switch tab
     */
    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    /**
     * Method untuk kembali ke halaman index
     */
    public function back(): void
    {
        $this->redirect(route('admin.galleries.index'), navigate: true);
    }

    /**
     * Computed property untuk mendapatkan gambar gallery
     */
    public function getGalleryImagesProperty()
    {
        return $this->gallery->images()->ordered()->get();
    }

    /**
     * Render component
     */
    public function render(): View
    {
        return view('livewire.admin.galleries.edit', [
            'galleryImages' => $this->galleryImages,
        ]);
    }
}