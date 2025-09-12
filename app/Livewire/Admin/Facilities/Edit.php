<?php

namespace App\Livewire\Admin\Facilities;

use App\Models\Facility;
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
    public ?UploadedFile $gambar = null;

    /**
     * Gambar lama untuk preview
     */
    public ?string $currentImage = null;

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
        $this->currentImage = $this->facility->gambar;
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
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048' // 2MB
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
    public function updatedGambar()
    {
        $this->validateOnly('gambar');
        
        if ($this->gambar) {
            if ($this->gambar->getSize() > 2048 * 1024) {
                $this->addError('gambar', 'Ukuran file tidak boleh lebih dari 2MB.');
                $this->gambar = null;
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
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Format gambar harus: jpeg, jpg, png, gif, atau webp',
            'gambar.max' => 'Ukuran gambar maksimal 2MB'
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
            'gambar' => 'gambar fasilitas'
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

        // Handle gambar upload jika ada gambar baru
        if ($this->gambar) {
            try {
                // Hapus gambar lama jika ada
                if ($this->facility->gambar) {
                    $this->imageService->deleteOldImage($this->facility->gambar);
                }
                
                // Upload gambar baru dengan error handling
                $data['gambar'] = $this->imageService->convertToWebP(
                    $this->gambar,
                    'facilities/images'
                );
                
                // Validasi bahwa file berhasil disimpan
                if (!$data['gambar'] || !Storage::exists($data['gambar'])) {
                    throw new \Exception('Gagal menyimpan file gambar.');
                }
                
            } catch (\Exception $e) {
                Log::error('Error uploading facility image', [
                    'facility_id' => $this->facility->id,
                    'error' => $e->getMessage(),
                    'file_size' => $this->gambar->getSize(),
                    'file_type' => $this->gambar->getMimeType()
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
            $this->gambar = null; // Reset file input
            
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
     * Hapus gambar fasilitas
     */
    public function removeImage(): void
    {
        try {
            if ($this->facility->gambar) {
                DB::transaction(function () {
                    // Hapus file gambar
                    $this->imageService->deleteOldImage($this->facility->gambar);
                    
                    // Update database
                    $this->facility->update(['gambar' => null]);
                    
                    // Log aktivitas
                    Log::info('Gambar fasilitas dihapus', [
                        'facility_id' => $this->facility->id,
                        'facility_name' => $this->facility->nama,
                        'deleted_by' => Auth::id()
                    ]);
                });
                
                $this->currentImage = null;
                $this->success('Gambar berhasil dihapus');
            } else {
                $this->warning('Tidak ada gambar untuk dihapus');
            }
        } catch (\Exception $e) {
            Log::error('Error removing facility image', [
                'facility_id' => $this->facility->id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);
            
            $this->error('Gagal menghapus gambar. Silakan coba lagi.');
        }
    }

    /**
     * Batal dan kembali ke halaman index
     */
    public function cancel(): void
    {
        // Reset form jika ada perubahan
        if ($this->gambar) {
            $this->gambar = null;
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
     * Get URL gambar saat ini
     */
    public function getCurrentImageUrlProperty(): ?string
    {
        return $this->currentImage ? Storage::url($this->currentImage) : null;
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