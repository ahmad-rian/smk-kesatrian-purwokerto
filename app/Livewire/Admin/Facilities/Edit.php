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
 * @version 1.0
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
        $this->study_program_id = $this->facility->study_program_id;

        // Load existing images
        $this->currentImages = $this->facility->images()->orderBy('urutan')->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => Storage::url($image->gambar),
                'alt_text' => $image->alt_text,
                'is_primary' => $image->is_primary
            ];
        })->toArray();

        // Fallback ke gambar lama jika tidak ada images
        if (empty($this->currentImages) && $this->facility->gambar) {
            $this->currentImages = [[
                'id' => null,
                'url' => Storage::url($this->facility->gambar),
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
                'in:laboratorium,perpustakaan,olahraga,aula,kantin,asrama,parkir,lainnya'
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
        $uploadedImages = [];
        $currentOrder = $this->facility->images()->max('urutan') ?? 0;

        foreach (array_filter($this->images) as $image) {
            $imagePath = $this->imageService->convertToWebP(
                $image,
                'facilities/images'
            );

            if ($imagePath && Storage::exists($imagePath)) {
                $uploadedImages[] = [
                    'facility_id' => $this->facility->id,
                    'gambar' => $imagePath,
                    'alt_text' => $this->facility->nama,
                    'urutan' => ++$currentOrder,
                    'is_primary' => count($this->currentImages) === 0 && count($uploadedImages) === 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        if (!empty($uploadedImages)) {
            FacilityImage::insert($uploadedImages);
        }
    }

    /**
     * Hapus gambar fasilitas berdasarkan ID
     */
    public function removeImage($imageId): void
    {
        try {
            $image = FacilityImage::where('facility_id', $this->facility->id)
                ->where('id', $imageId)
                ->first();

            if ($image) {
                DB::transaction(function () use ($image) {
                    // Hapus file gambar
                    $this->imageService->deleteOldImage($image->gambar);

                    // Hapus dari database
                    $image->delete();

                    // Log aktivitas
                    Log::info('Gambar fasilitas dihapus', [
                        'facility_id' => $this->facility->id,
                        'image_id' => $image->id,
                        'deleted_by' => Auth::id()
                    ]);
                });

                // Reload current images
                $this->loadCurrentData();
                $this->success('Gambar berhasil dihapus');
            } else {
                $this->warning('Gambar tidak ditemukan');
            }
        } catch (\Exception $e) {
            Log::error('Error removing facility image', [
                'facility_id' => $this->facility->id,
                'image_id' => $imageId,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            $this->error('Gagal menghapus gambar. Silakan coba lagi.');
        }
    }

    /**
     * Set gambar sebagai primary
     */
    public function setPrimaryImage($imageId): void
    {
        try {
            DB::transaction(function () use ($imageId) {
                // Reset semua gambar menjadi bukan primary
                FacilityImage::where('facility_id', $this->facility->id)
                    ->update(['is_primary' => false]);

                // Set gambar terpilih sebagai primary
                FacilityImage::where('facility_id', $this->facility->id)
                    ->where('id', $imageId)
                    ->update(['is_primary' => true]);
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
     * Batal dan kembali ke halaman index
     */
    public function cancel(): void
    {
        // Reset form jika ada perubahan
        if ($this->images) {
            $this->images = [];
        }

        // Log aktivitas cancel
        Log::info('Edit fasilitas dibatalkan', [
            'facility_id' => $this->facility->id,
            'user_id' => Auth::id()
        ]);

        $this->redirect(route('admin.facilities.index'), navigate: true);
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
        return [
            ['value' => 'laboratorium', 'label' => 'Laboratorium'],
            ['value' => 'perpustakaan', 'label' => 'Perpustakaan'],
            ['value' => 'olahraga', 'label' => 'Fasilitas Olahraga'],
            ['value' => 'aula', 'label' => 'Aula'],
            ['value' => 'kantin', 'label' => 'Kantin'],
            ['value' => 'asrama', 'label' => 'Asrama'],
            ['value' => 'parkir', 'label' => 'Area Parkir'],
            ['value' => 'lainnya', 'label' => 'Lainnya'],
        ];
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
