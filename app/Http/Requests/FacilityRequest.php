<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

/**
 * Request class untuk validasi data fasilitas
 * 
 * Menangani validasi untuk operasi Create dan Update fasilitas
 * dengan aturan yang berbeda berdasarkan context
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class FacilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $facilityId = $this->route('facility')?->id;
        
        return [
            'nama' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('facilities', 'nama')->ignore($facilityId)
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
            'kategori' => [
                'nullable',
                'string',
                'max:100',
                'in:laboratorium,perpustakaan,olahraga,aula,kantin,asrama,parkir,lainnya'
            ],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp,bmp',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=4000,max_height=4000'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Nama fasilitas
            'nama.required' => 'Nama fasilitas wajib diisi.',
            'nama.string' => 'Nama fasilitas harus berupa teks.',
            'nama.min' => 'Nama fasilitas minimal :min karakter.',
            'nama.max' => 'Nama fasilitas maksimal :max karakter.',
            'nama.unique' => 'Nama fasilitas sudah digunakan, silakan pilih nama lain.',
            
            // Deskripsi
            'deskripsi.required' => 'Deskripsi fasilitas wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.min' => 'Deskripsi minimal :min karakter.',
            'deskripsi.max' => 'Deskripsi maksimal :max karakter.',
            
            // Program Studi
            'study_program_id.required' => 'Program studi wajib dipilih.',
            'study_program_id.exists' => 'Program studi yang dipilih tidak valid.',
            
            // Kategori
            'kategori.string' => 'Kategori harus berupa teks.',
            'kategori.max' => 'Kategori maksimal :max karakter.',
            'kategori.in' => 'Kategori yang dipilih tidak valid. Pilih salah satu: Laboratorium, Perpustakaan, Olahraga, Aula, Kantin, Asrama, Parkir, atau Lainnya.',
            
            // Gambar
            'gambar.image' => 'File yang diupload harus berupa gambar.',
            'gambar.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, GIF, WebP, atau BMP.',
            'gambar.max' => 'Ukuran gambar maksimal :max KB (2MB).',
            'gambar.dimensions' => 'Dimensi gambar minimal 100x100 pixel dan maksimal 4000x4000 pixel.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama fasilitas',
            'deskripsi' => 'deskripsi fasilitas',
            'study_program_id' => 'program studi',
            'gambar' => 'gambar fasilitas'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Bersihkan dan format data sebelum validasi
        $this->merge([
            'nama' => $this->cleanString($this->nama),
            'deskripsi' => $this->cleanString($this->deskripsi),
        ]);
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Validasi tambahan setelah validasi dasar berhasil
        $this->validateBusinessRules();
    }

    /**
     * Validasi business rules tambahan
     */
    private function validateBusinessRules(): void
    {
        // Cek apakah nama fasilitas mengandung kata-kata yang tidak pantas
        $forbiddenWords = ['test', 'dummy', 'sample'];
        $nama = strtolower($this->nama ?? '');
        
        foreach ($forbiddenWords as $word) {
            if (str_contains($nama, $word)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'nama' => 'Nama fasilitas tidak boleh mengandung kata "' . $word . '".'
                ]);
            }
        }
    }

    /**
     * Bersihkan string dari karakter yang tidak diinginkan
     */
    private function cleanString(?string $value): ?string
    {
        if (!$value) {
            return null;
        }
        
        // Hapus whitespace berlebih dan karakter kontrol
        $cleaned = trim(preg_replace('/\s+/', ' ', $value));
        
        // Hapus karakter yang berpotensi berbahaya
        $cleaned = preg_replace('/[<>"\']/', '', $cleaned);
        
        return $cleaned ?: null;
    }

    /**
     * Get validated data dengan format yang sudah dibersihkan
     */
    public function getCleanedData(): array
    {
        $validated = $this->validated();
        
        return [
            'nama' => $this->cleanString($validated['nama']),
            'deskripsi' => $this->cleanString($validated['deskripsi']),
            'study_program_id' => $validated['study_program_id'],
            'gambar' => $validated['gambar'] ?? null
        ];
    }

    /**
     * Cek apakah request ini untuk update
     */
    public function isUpdate(): bool
    {
        return $this->route('facility') !== null;
    }

    /**
     * Cek apakah request ini untuk create
     */
    public function isCreate(): bool
    {
        return !$this->isUpdate();
    }
}