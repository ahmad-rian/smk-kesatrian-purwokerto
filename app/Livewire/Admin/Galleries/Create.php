<?php

namespace App\Livewire\Admin\Galleries;

use App\Models\Gallery;
use App\Services\ImageConversionService;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

/**
 * Livewire Component untuk membuat Gallery baru
 * 
 * Fitur yang tersedia:
 * - Form input data gallery
 * - Upload gambar sampul dengan preview
 * - Auto-generate slug dari judul
 * - Validasi input yang komprehensif
 * - Konversi gambar otomatis ke WebP
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Create extends Component
{
    use WithFileUploads, Toast;

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

    /**
     * Properties untuk UI state
     */
    public bool $isLoading = false;
    public bool $autoGenerateSlug = true;

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
     * Mount component dengan data default
     */
    public function mount(): void
    {
        $this->urutan = Gallery::getNextUrutan();
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
        return Gallery::validationRules();
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
     * Method untuk menyimpan gallery
     */
    public function save(): void
    {
        $this->isLoading = true;

        try {
            // Validasi input
            $validatedData = $this->validate();

            DB::beginTransaction();

            // Siapkan data untuk disimpan
            $data = [
                'judul' => $this->judul,
                'slug' => $this->slug ?: Str::slug($this->judul),
                'deskripsi' => $this->deskripsi ?: null,
                'aktif' => $this->aktif,
                'urutan' => $this->urutan,
            ];

            // Handle upload gambar sampul jika ada
            if ($this->gambar_sampul) {
                $data['gambar_sampul'] = $this->imageService->convertToWebP(
                    $this->gambar_sampul,
                    'galleries/sampul'
                );
            }

            // Simpan gallery
            $gallery = Gallery::create($data);

            DB::commit();

            $this->success(
                title: 'Berhasil!',
                description: "Gallery '{$gallery->judul}' berhasil dibuat.",
                position: 'toast-top toast-end'
            );

            // Redirect ke halaman edit untuk menambah gambar
            $this->redirect(route('admin.galleries.edit', $gallery->id), navigate: true);

        } catch (ValidationException $e) {
            $this->error(
                title: 'Validasi Gagal!',
                description: 'Silakan periksa kembali data yang diinput.',
                position: 'toast-top toast-end'
            );
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating gallery: ' . $e->getMessage(), [
                'data' => $this->all(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->error(
                title: 'Gagal!',
                description: 'Terjadi kesalahan saat menyimpan gallery.',
                position: 'toast-top toast-end'
            );
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Method untuk reset form
     */
    public function resetForm(): void
    {
        $this->reset([
            'judul',
            'slug', 
            'deskripsi',
            'gambar_sampul'
        ]);
        $this->aktif = true;
        $this->urutan = Gallery::getNextUrutan();
        $this->autoGenerateSlug = true;
    }

    /**
     * Method untuk preview gambar
     */
    public function getImagePreviewProperty(): ?string
    {
        return $this->gambar_sampul ? $this->gambar_sampul->temporaryUrl() : null;
    }

    /**
     * Method untuk menghapus gambar yang diupload
     */
    public function removeImage(): void
    {
        $this->gambar_sampul = null;
    }

    /**
     * Method untuk kembali ke halaman index
     */
    public function back(): void
    {
        $this->redirect(route('admin.galleries.index'), navigate: true);
    }

    /**
     * Render component
     */
    public function render(): View
    {
        return view('livewire.admin.galleries.create');
    }
}