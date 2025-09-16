<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
use App\Models\FacilityImage;
use App\Models\StudyProgram;
use App\Services\ImageConversionService;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

/**
 * Livewire Component untuk mengedit fasilitas
 * 
 * Menyediakan form untuk edit semua data fasilitas
 * dengan validasi dan upload gambar otomatis
 * 
 * @author Laravel Expert Agent
 * @version 1.2
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use Toast, WithFileUploads;

    /**
     * Instance fasilitas yang sedang diedit
     */
    public Facility $facility;

    /**
     * Form Properties - Data Fasilitas
     */
    public string $nama = '';
    public ?string $kategori = null;
    public string $deskripsi = '';
    public string $study_program_id = '';
    public $images = [];

    /**
     * Gambar lama untuk preview
     */
    public array $currentImages = [];

    /**
     * Properties untuk modal hapus gambar
     */
    public ?string $imageToDelete = null;
    public bool $showDeleteModal = false;

    /**
     * Service untuk konversi gambar
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
     * Mount component dengan data fasilitas
     */
    public function mount(Facility $facility): void
    {
        $this->facility = $facility;
        $this->loadCurrentData();
    }

    /**
     * Load data fasilitas ke form properties
     */
    private function loadCurrentData(): void
    {
        $this->nama = $this->facility->nama;
        $this->kategori = $this->facility->kategori;
        $this->deskripsi = $this->facility->deskripsi;
        $this->study_program_id = (string) $this->facility->study_program_id;

        // Load existing images
        $this->currentImages = $this->facility->images()->orderBy('urutan')->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => $image->gambar_url,
                'alt_text' => $image->alt_text,
                'is_primary' => $image->is_primary
            ];
        })->toArray();

        // Fallback ke gambar lama jika tidak ada images
        if (empty($this->currentImages) && $this->facility->gambar) {
            $this->currentImages = [[
                'id' => null,
                'url' => $this->facility->gambar_url,
                'alt_text' => $this->facility->nama,
                'is_primary' => true
            ]];
        }
    }

    /**
     * Aturan validasi untuk form
     */
    protected function rules(): array
    {
        return [
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'unique:facilities,nama,' . $this->facility->id
            ],
            'kategori' => [
                'nullable',
                'string',
                'max:100',
                'in:' . implode(',', array_keys(Facility::getAvailableCategories()))
            ],
            'deskripsi' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'study_program_id' => [
                'required',
                'exists:study_programs,id'
            ],
            'images.*' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048' // 2MB per file
            ],
            'images' => [
                'nullable',
                'array',
                'max:5' // Maksimal 5 gambar
            ]
        ];
    }

    /**
     * Real-time validation untuk field tertentu
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Validasi khusus untuk nama fasilitas
     */
    public function updatedNama()
    {
        $this->validateOnly('nama');

        if ($this->nama) {
            $this->nama = trim($this->nama);
        }
    }

    /**
     * Validasi khusus untuk gambar
     */
    public function updatedImages()
    {
        $this->validateOnly('images');

        if ($this->images) {
            // Validasi jumlah total gambar (existing + new)
            $totalImages = count($this->currentImages) + count(array_filter($this->images));
            if ($totalImages > 5) {
                $this->addError('images', 'Total gambar tidak boleh lebih dari 5.');
                $this->images = [];
                return;
            }

            // Validasi setiap file
            foreach ($this->images as $index => $image) {
                if ($image && $image->getSize() > 2048 * 1024) {
                    $this->addError("images.{$index}", 'Ukuran file tidak boleh lebih dari 2MB.');
                    unset($this->images[$index]);
                }
            }
        }
    }

    /**
     * Pesan error kustom untuk validasi
     */
    protected function messages(): array
    {
        return [
            'nama.required' => 'Nama fasilitas wajib diisi',
            'nama.min' => 'Nama fasilitas minimal 3 karakter',
            'nama.max' => 'Nama fasilitas maksimal 255 karakter',
            'nama.unique' => 'Nama fasilitas sudah digunakan',
            'kategori.max' => 'Kategori maksimal 100 karakter',
            'deskripsi.required' => 'Deskripsi fasilitas wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
            'study_program_id.required' => 'Program studi wajib dipilih',
            'study_program_id.exists' => 'Program studi tidak valid',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar harus: jpeg, jpg, png, gif, atau webp',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
            'images.max' => 'Maksimal 5 gambar yang dapat diunggah'
        ];
    }

    /**
     * Atribut kustom untuk pesan error
     */
    protected function validationAttributes(): array
    {
        return [
            'nama' => 'nama fasilitas',
            'kategori' => 'kategori',
            'deskripsi' => 'deskripsi',
            'study_program_id' => 'program studi',
            'images' => 'gambar fasilitas'
        ];
    }

    /**
     * Validasi input menggunakan rules yang sudah didefinisikan
     */
    private function validateInput(): array
    {
        try {
            return $this->validate();
        } catch (ValidationException $e) {
            $this->error('Terdapat kesalahan pada form. Silakan periksa kembali.');
            throw $e;
        }
    }

    /**
     * Persiapkan data untuk update ke database
     */
    private function prepareDataForUpdate(array $validatedData): array
    {
        $data = [
            'nama' => trim($this->nama),
            'kategori' => $this->kategori ? trim($this->kategori) : null,
            'deskripsi' => trim($this->deskripsi),
            'study_program_id' => $this->study_program_id,
        ];

        // Handle multiple images upload jika ada gambar baru
        if (!empty($this->images) && count(array_filter($this->images)) > 0) {
            try {
                // Upload gambar baru
                $this->uploadNewImages();
            } catch (\Exception $e) {
                Log::error('Error uploading facility images', [
                    'facility_id' => $this->facility->id,
                    'error' => $e->getMessage(),
                    'user_id' => Auth::id()
                ]);

                throw new \Exception('Gagal mengupload gambar: ' . $e->getMessage());
            }
        }

        return $data;
    }

    /**
     * Update fasilitas
     */
    public function update(): void
    {
        try {
            // Validasi input
            $validatedData = $this->validateInput();

            // Validasi business rules tambahan
            $this->validateBusinessRules();

            // Persiapkan data untuk update
            $data = $this->prepareDataForUpdate($validatedData);

            // Update ke database dengan transaction
            DB::transaction(function () use ($data) {
                $this->facility->update($data);

                // Log untuk debugging
                Log::info('Fasilitas berhasil diupdate', [
                    'facility_id' => $this->facility->id,
                    'nama' => $this->facility->nama,
                    'updated_by' => Auth::id(),
                    'updated_at' => now()
                ]);
            });

            // Refresh data dan tampilkan pesan sukses
            $this->facility->refresh();
            $this->loadCurrentData();
            $this->images = []; // Reset file input

            $this->success('Fasilitas "' . $this->facility->nama . '" berhasil diperbarui!');

            // Redirect ke halaman index
            $this->redirect(route('admin.facilities.index'), navigate: true);
        } catch (ValidationException $e) {
            // Error sudah ditangani di validateInput()
            Log::warning('Validation error saat update fasilitas', [
                'facility_id' => $this->facility->id,
                'errors' => $e->errors(),
                'user_id' => Auth::id()
            ]);
            return;
        } catch (\Exception $e) {
            // Log error detail untuk debugging
            Log::error('Error updating facility', [
                'facility_id' => $this->facility->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'data' => [
                    'nama' => $this->nama,
                    'study_program_id' => $this->study_program_id,
                    'kategori' => $this->kategori
                ]
            ]);

            $this->error('Gagal memperbarui fasilitas. Silakan periksa kembali data dan coba lagi.');
        }
    }

    /**
     * Validasi business rules tambahan
     */
    private function validateBusinessRules(): void
    {
        // Cek apakah program studi masih aktif
        $studyProgram = StudyProgram::find($this->study_program_id);
        if (!$studyProgram) {
            $this->addError('study_program_id', 'Program studi tidak ditemukan.');
            throw new ValidationException(validator([], []));
        }

        // Cek duplikasi nama dalam program studi yang sama (kecuali record saat ini)
        $existingFacility = Facility::where('nama', trim($this->nama))
            ->where('study_program_id', $this->study_program_id)
            ->where('id', '!=', $this->facility->id)
            ->first();

        if ($existingFacility) {
            $this->addError('nama', 'Fasilitas dengan nama ini sudah ada di program studi yang sama.');
            throw new ValidationException(validator([], []));
        }
    }

    /**
     * Upload gambar baru
     */
    private function uploadNewImages(): void
    {
        $currentOrder = $this->facility->images()->max('urutan') ?? 0;
        $isFirstImage = count($this->currentImages) === 0;

        foreach (array_filter($this->images) as $image) {
            $imagePath = $this->imageService->convertToWebP(
                $image,
                'facilities/images'
            );

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                FacilityImage::create([
                    'facility_id' => $this->facility->id,
                    'gambar' => $imagePath,
                    'alt_text' => $this->facility->nama,
                    'urutan' => ++$currentOrder,
                    'is_primary' => $isFirstImage && $currentOrder === 1
                ]);
            }
        }
    }

    /**
     * Set gambar sebagai primary
     */
    public function setPrimaryImage($imageId): void
    {
        if (!$imageId) {
            $this->error('ID gambar tidak valid');
            return;
        }

        try {
            DB::transaction(function () use ($imageId) {
                // Reset semua gambar menjadi bukan primary
                FacilityImage::where('facility_id', $this->facility->id)
                    ->update(['is_primary' => false]);

                // Set gambar terpilih sebagai primary
                $updated = FacilityImage::where('facility_id', $this->facility->id)
                    ->where('id', $imageId)
                    ->update(['is_primary' => true]);

                if (!$updated) {
                    throw new \Exception('Gambar tidak ditemukan');
                }

                Log::info('Primary image updated', [
                    'facility_id' => $this->facility->id,
                    'new_primary_image_id' => $imageId,
                    'updated_by' => Auth::id()
                ]);
            });

            $this->loadCurrentData();
            $this->success('Gambar utama berhasil diubah');
        } catch (\Exception $e) {
            Log::error('Error setting primary image', [
                'facility_id' => $this->facility->id,
                'image_id' => $imageId,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $this->error('Gagal mengubah gambar utama.');
        }
    }

    /**
     * Konfirmasi penghapusan gambar
     */
    public function confirmDeleteImage($imageId): void
    {
        if (!$imageId) {
            $this->error('ID gambar tidak valid');
            return;
        }

        // Cek apakah gambar ada dan milik fasilitas ini
        $image = FacilityImage::where('facility_id', $this->facility->id)
            ->where('id', $imageId)
            ->first();

        if (!$image) {
            $this->error('Gambar tidak ditemukan');
            return;
        }

        Log::info('confirmDeleteImage called', [
            'facility_id' => $this->facility->id,
            'image_id' => $imageId,
            'user_id' => Auth::id()
        ]);

        $this->imageToDelete = $imageId;
        $this->showDeleteModal = true;
    }

    /**
     * Batalkan penghapusan gambar
     */
    public function cancelDeleteImage(): void
    {
        $this->imageToDelete = null;
        $this->showDeleteModal = false;
    }

    /**
     * Hapus gambar fasilitas berdasarkan ID
     */
    public function removeImage(): void
    {
        if (!$this->imageToDelete) {
            $this->warning('Tidak ada gambar yang dipilih untuk dihapus');
            $this->showDeleteModal = false;
            return;
        }

        try {
            $image = FacilityImage::where('facility_id', $this->facility->id)
                ->where('id', $this->imageToDelete)
                ->first();

            if (!$image) {
                $this->warning('Gambar tidak ditemukan');
                $this->resetDeleteModal();
                return;
            }

            DB::transaction(function () use ($image) {
                // Hapus file gambar dari storage
                if ($image->gambar && Storage::disk('public')->exists($image->gambar)) {
                    Storage::disk('public')->delete($image->gambar);
                }

                // Jika ini adalah gambar primary, set gambar lain sebagai primary
                if ($image->is_primary) {
                    $nextPrimaryImage = FacilityImage::where('facility_id', $this->facility->id)
                        ->where('id', '!=', $image->id)
                        ->orderBy('urutan')
                        ->first();

                    if ($nextPrimaryImage) {
                        $nextPrimaryImage->update(['is_primary' => true]);
                    }
                }

                // Hapus dari database
                $image->delete();

                Log::info('Gambar fasilitas dihapus', [
                    'facility_id' => $this->facility->id,
                    'image_id' => $image->id,
                    'image_path' => $image->gambar,
                    'was_primary' => $image->is_primary,
                    'deleted_by' => Auth::id()
                ]);
            });

            $this->resetDeleteModal();
            $this->loadCurrentData();
            $this->success('Gambar berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error removing facility image', [
                'facility_id' => $this->facility->id,
                'image_id' => $this->imageToDelete,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $this->resetDeleteModal();
            $this->error('Gagal menghapus gambar. Silakan coba lagi.');
        }
    }

    /**
     * Execute hapus gambar setelah konfirmasi
     */
    public function executeDeleteImage($imageId): void
    {
        if (!$imageId) {
            $this->error('ID gambar tidak valid');
            return;
        }

        try {
            $image = FacilityImage::where('facility_id', $this->facility->id)
                ->where('id', $imageId)
                ->first();

            if (!$image) {
                $this->warning('Gambar tidak ditemukan');
                return;
            }

            DB::transaction(function () use ($image) {
                // Hapus file gambar dari storage
                if ($image->gambar && Storage::disk('public')->exists($image->gambar)) {
                    Storage::disk('public')->delete($image->gambar);
                }

                // Jika ini adalah gambar primary, set gambar lain sebagai primary
                if ($image->is_primary) {
                    $nextPrimaryImage = FacilityImage::where('facility_id', $this->facility->id)
                        ->where('id', '!=', $image->id)
                        ->orderBy('urutan')
                        ->first();

                    if ($nextPrimaryImage) {
                        $nextPrimaryImage->update(['is_primary' => true]);
                    }
                }

                // Hapus dari database
                $image->delete();

                Log::info('Gambar fasilitas dihapus (execute)', [
                    'facility_id' => $this->facility->id,
                    'image_id' => $image->id,
                    'image_path' => $image->gambar,
                    'was_primary' => $image->is_primary,
                    'deleted_by' => Auth::id()
                ]);
            });

            $this->loadCurrentData();
            $this->success('Gambar berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error executing delete image', [
                'facility_id' => $this->facility->id,
                'image_id' => $imageId,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $this->error('Gagal menghapus gambar. Silakan coba lagi.');
        }
    }

    /**
     * Reset modal delete state
     */
    private function resetDeleteModal(): void
    {
        $this->imageToDelete = null;
        $this->showDeleteModal = false;
    }

    /**
     * Remove preview image dari array images
     */
    public function removePreviewImage($index): void
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // Reset array index

            Log::info('Preview image removed', [
                'facility_id' => $this->facility->id,
                'removed_index' => $index,
                'remaining_images' => count($this->images),
                'user_id' => Auth::id()
            ]);
        }
    }

    /**
     * Batal dan kembali ke halaman index
     */
    public function cancel(): void
    {
        if ($this->images) {
            $this->images = [];
        }

        Log::info('Edit fasilitas dibatalkan', [
            'facility_id' => $this->facility->id,
            'user_id' => Auth::id()
        ]);

        $this->redirect(route('admin.facilities.index'), navigate: true);
    }

    /**
     * Duplikasi fasilitas dengan konfirmasi
     */
    public function duplicate(): void
    {
        $this->js("
            if (confirm('Yakin ingin menduplikasi fasilitas ini?')) {
                \$wire.call('executeDuplicate');
            }
        ");
    }

    /**
     * Execute duplikasi fasilitas
     */
    public function executeDuplicate(): void
    {
        try {
            DB::transaction(function () {
                // Clone fasilitas
                $newFacility = $this->facility->replicate();
                $newFacility->nama = $this->facility->nama . ' (Copy)';
                $newFacility->save();

                // Clone images if any
                foreach ($this->facility->images as $image) {
                    $newImage = $image->replicate();
                    $newImage->facility_id = $newFacility->id;
                    $newImage->save();
                }

                Log::info('Fasilitas berhasil diduplikasi', [
                    'original_facility_id' => $this->facility->id,
                    'new_facility_id' => $newFacility->id,
                    'user_id' => Auth::id()
                ]);
            });

            $this->success('Fasilitas berhasil diduplikasi!');
            $this->redirect(route('admin.facilities.index'), navigate: true);
        } catch (\Exception $e) {
            Log::error('Error duplicating facility', [
                'facility_id' => $this->facility->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $this->error('Gagal menduplikasi fasilitas.');
        }
    }

    /**
     * Hapus fasilitas dengan konfirmasi
     */
    public function delete(): void
    {
        $this->js("
            if (confirm('Yakin ingin menghapus fasilitas ini?\\n\\nTindakan ini tidak dapat dibatalkan.')) {
                \$wire.call('executeDelete');
            }
        ");
    }

    /**
     * Execute hapus fasilitas
     */
    public function executeDelete(): void
    {
        try {
            DB::transaction(function () {
                // Hapus semua gambar terkait
                foreach ($this->facility->images as $image) {
                    if ($image->gambar && Storage::disk('public')->exists($image->gambar)) {
                        Storage::disk('public')->delete($image->gambar);
                    }
                    $image->delete();
                }

                // Hapus fasilitas
                $facilityName = $this->facility->nama;
                $this->facility->delete();

                Log::info('Fasilitas berhasil dihapus', [
                    'facility_id' => $this->facility->id,
                    'facility_name' => $facilityName,
                    'user_id' => Auth::id()
                ]);
            });

            $this->success('Fasilitas berhasil dihapus!');
            $this->redirect(route('admin.facilities.index'), navigate: true);
        } catch (\Exception $e) {
            Log::error('Error deleting facility', [
                'facility_id' => $this->facility->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $this->error('Gagal menghapus fasilitas.');
        }
    }

    /**
     * Method untuk preview gambar baru
     */
    public function getImagePreviewsProperty(): array
    {
        $previews = [];

        if (!empty($this->images)) {
            foreach ($this->images as $index => $image) {
                if ($image) {
                    try {
                        if ($image instanceof UploadedFile) {
                            $previews[$index] = $image->temporaryUrl();
                        }
                    } catch (\Exception $e) {
                        Log::warning('Failed to generate temporary URL for image preview: ' . $e->getMessage());
                        $previews[$index] = null;
                    }
                }
            }
        }

        return $previews;
    }

    /**
     * Get primary image URL
     */
    public function getPrimaryImageUrlProperty(): ?string
    {
        $primaryImage = collect($this->currentImages)->firstWhere('is_primary', true);
        return $primaryImage ? $primaryImage['url'] : (isset($this->currentImages[0]) ? $this->currentImages[0]['url'] : null);
    }

    /**
     * Get daftar program studi untuk dropdown
     */
    public function getStudyProgramsProperty()
    {
        return StudyProgram::orderBy('nama')->get();
    }

    /**
     * Computed property untuk mendapatkan opsi kategori
     */
    public function getKategoriOptionsProperty()
    {
        $categories = [];
        foreach (Facility::getAvailableCategories() as $value => $label) {
            $categories[] = ['value' => $value, 'label' => $label];
        }
        return $categories;
    }

    /**
     * Render component
     */
    public function render(): View
    {
        return view('livewire.admin.facilities.edit', [
            'studyPrograms' => $this->studyPrograms
        ]);
    }
}
