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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

/**
 * Livewire Component untuk membuat fasilitas baru
 * 
 * Menyediakan form untuk input semua data fasilitas
 * dengan validasi dan upload gambar otomatis
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Create extends Component
{
    use Toast, WithFileUploads;

    /**
     * Form Properties - Data Fasilitas
     */
    public string $nama = '';
    public ?string $kategori = null;
    public string $deskripsi = '';
    public string $study_program_id = '';
    public ?UploadedFile $gambar = null; // Backward compatibility
    public array $images = []; // Multiple images (1-5)

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
     * Real-time validation rules
     */
    protected function rules(): array
    {
        return [
            'nama' => 'required|string|max:255|unique:facilities,nama',
            'deskripsi' => 'required|string|min:10|max:1000',
            'study_program_id' => 'required|exists:study_programs,id',
            'kategori' => 'nullable|string|max:100|in:' . implode(',', array_keys(Facility::getAvailableCategories())),
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Backward compatibility
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images' => 'nullable|array|min:1|max:5',
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

        // Auto-generate slug atau format nama jika diperlukan
        if ($this->nama) {
            $this->nama = trim($this->nama);
        }
    }

    /**
     * Validasi khusus untuk gambar
     */
    public function updatedGambar()
    {
        $this->validateOnly('gambar');

        if ($this->gambar) {
            // Validasi tambahan untuk ukuran file
            if ($this->gambar->getSize() > 2048 * 1024) {
                $this->addError('gambar', 'Ukuran file tidak boleh lebih dari 2MB.');
                $this->gambar = null;
            }
        }
    }

    /**
     * Validasi khusus untuk multiple images
     */
    public function updatedImages()
    {
        $this->validateOnly('images');

        // Validasi setiap gambar
        foreach ($this->images as $index => $image) {
            if ($image && $image instanceof UploadedFile) {
                // Validasi ukuran file
                if ($image->getSize() > 2048 * 1024) {
                    $this->addError("images.{$index}", 'Ukuran file tidak boleh lebih dari 2MB.');
                    unset($this->images[$index]);
                }
            }
        }

        // Reset array index
        $this->images = array_values(array_filter($this->images));
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
            'deskripsi.required' => 'Deskripsi fasilitas wajib diisi',
            'deskripsi.min' => 'Deskripsi minimal 10 karakter',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter',
            'study_program_id.required' => 'Program studi wajib dipilih',
            'study_program_id.exists' => 'Program studi tidak valid',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus: jpeg, jpg, png, gif, atau webp',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar harus: jpeg, jpg, png, gif, atau webp',
            'images.*.max' => 'Ukuran gambar maksimal 2MB',
            'images.min' => 'Minimal upload 1 gambar',
            'images.max' => 'Maksimal upload 5 gambar'
        ];
    }

    /**
     * Atribut kustom untuk pesan error
     */
    protected function validationAttributes(): array
    {
        return [
            'nama' => 'nama fasilitas',
            'deskripsi' => 'deskripsi',
            'study_program_id' => 'program studi',
            'gambar' => 'gambar fasilitas',
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
     * Persiapkan data untuk disimpan ke database
     */
    private function prepareDataForSave(array $validatedData): array
    {
        $data = [
            'nama' => trim($this->nama),
            'kategori' => $this->kategori,
            'deskripsi' => trim($this->deskripsi),
            'study_program_id' => $this->study_program_id,
        ];

        // Handle gambar upload jika ada (backward compatibility)
        if ($this->gambar) {
            try {
                $data['gambar'] = $this->imageService->convertToWebP(
                    $this->gambar,
                    'facilities/images'
                );
            } catch (\Exception $e) {
                Log::error('Error saat mengupload gambar fasilitas', [
                    'error' => $e->getMessage(),
                    'file_name' => $this->gambar->getClientOriginalName(),
                    'file_size' => $this->gambar->getSize()
                ]);
                throw new \Exception('Gagal mengupload gambar. Silakan coba lagi dengan file yang berbeda.');
            }
        }

        return $data;
    }

    /**
     * Reset form ke kondisi awal
     */
    private function resetForm(): void
    {
        $this->nama = '';
        $this->deskripsi = '';
        $this->study_program_id = '';
        $this->gambar = null;
        $this->images = [];
    }

    /**
     * Simpan fasilitas baru
     */
    public function save(): void
    {
        try {
            // Validasi input
            $validatedData = $this->validateInput();

            // Validasi business rules tambahan
            $this->validateBusinessRules();

            // Persiapkan data untuk disimpan
            $data = $this->prepareDataForSave($validatedData);

            // Simpan ke database dengan transaction
            DB::transaction(function () use ($data) {
                $facility = Facility::create($data);

                // Upload multiple images jika ada
                if (!empty($this->images)) {
                    $this->uploadMultipleImages($facility);
                }

                // Log untuk debugging
                Log::info('Fasilitas baru berhasil dibuat', [
                    'facility_id' => $facility->id,
                    'nama' => $facility->nama,
                    'study_program_id' => $facility->study_program_id,
                    'kategori' => $facility->kategori,
                    'images_count' => count($this->images)
                ]);
            });

            // Reset form dan tampilkan pesan sukses
            $this->resetForm();
            $this->success('Fasilitas berhasil ditambahkan!');

            // Redirect ke halaman index
            $this->redirect(route('admin.facilities.index'), navigate: true);
        } catch (ValidationException $e) {
            // Error sudah ditangani di validateInput()
            Log::warning('Validation error saat membuat fasilitas', [
                'errors' => $e->errors(),
                'input_data' => [
                    'nama' => $this->nama,
                    'study_program_id' => $this->study_program_id,
                    'kategori' => $this->kategori
                ]
            ]);
            return;
        } catch (\Exception $e) {
            Log::error('Error saat membuat fasilitas', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_data' => [
                    'nama' => $this->nama,
                    'study_program_id' => $this->study_program_id,
                    'kategori' => $this->kategori
                ]
            ]);
            $this->error('Gagal menyimpan fasilitas. Silakan periksa kembali dan coba lagi.');
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

        // Cek duplikasi nama dalam program studi yang sama
        $existingFacility = Facility::where('nama', trim($this->nama))
            ->where('study_program_id', $this->study_program_id)
            ->first();

        if ($existingFacility) {
            $this->addError('nama', 'Fasilitas dengan nama ini sudah ada di program studi yang sama.');
            throw new ValidationException(validator([], []));
        }
    }

    /**
     * Batal dan kembali ke halaman index
     */
    public function cancel(): void
    {
        $this->redirect(route('admin.facilities.index'), navigate: true);
    }

    /**
     * Upload multiple images untuk fasilitas
     */
    private function uploadMultipleImages(Facility $facility): void
    {
        foreach ($this->images as $index => $image) {
            if ($image instanceof UploadedFile) {
                try {
                    $facilityImage = new FacilityImage([
                        'facility_id' => $facility->id,
                        'urutan' => $index + 1,
                        'is_primary' => $index === 0, // Gambar pertama sebagai primary
                        'alt_text' => "Gambar fasilitas {$facility->nama} - " . ($index + 1)
                    ]);

                    $facilityImage->gambar = $image;
                    $facilityImage->save();
                } catch (\Exception $e) {
                    Log::error('Error saat mengupload gambar fasilitas', [
                        'error' => $e->getMessage(),
                        'facility_id' => $facility->id,
                        'image_index' => $index,
                        'file_name' => $image->getClientOriginalName()
                    ]);
                    throw new \Exception("Gagal mengupload gambar ke-" . ($index + 1) . ". Silakan coba lagi.");
                }
            }
        }
    }

    /**
     * Remove image dari array
     */
    public function removeImage($index): void
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images); // Reset array index
        }
    }

    /**
     * Method untuk preview gambar yang diupload (backward compatibility)
     */
    public function getImagePreviewProperty(): ?string
    {
        if (!$this->gambar) {
            return null;
        }

        try {
            // Pastikan file adalah instance UploadedFile yang valid
            if ($this->gambar instanceof UploadedFile) {
                return $this->gambar->temporaryUrl();
            }
        } catch (\Exception $e) {
            // Jika temporaryUrl gagal, return null untuk fallback ke placeholder
            Log::warning('Failed to generate temporary URL for image preview: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Method untuk preview multiple images
     */
    public function getImagePreviewsProperty(): array
    {
        $previews = [];

        foreach ($this->images as $index => $image) {
            if ($image instanceof UploadedFile) {
                try {
                    $previews[$index] = $image->temporaryUrl();
                } catch (\Exception $e) {
                    Log::warning("Failed to generate temporary URL for image {$index}: " . $e->getMessage());
                    $previews[$index] = null;
                }
            }
        }

        return $previews;
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
        return view('livewire.admin.facilities.create', [
            'studyPrograms' => $this->studyPrograms
        ]);
    }
}
