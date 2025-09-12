<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi data Gallery Image
 * 
 * Menangani validasi untuk operasi upload dan update gambar gallery
 * dengan aturan yang sesuai untuk file gambar
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class GalleryImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Gallery ID - wajib, harus ada di tabel galleries
            'gallery_id' => [
                'required',
                'string',
                Rule::exists('galleries', 'id'),
            ],

            // Gambar - wajib, file gambar dengan format dan ukuran tertentu
            'gambar' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120', // 5MB maksimal
                'dimensions:min_width=200,min_height=200,max_width=2000,max_height=2000',
            ],

            // Urutan - opsional, integer positif
            'urutan' => [
                'nullable',
                'integer',
                'min:1',
                'max:999',
            ],

            // Multiple images untuk upload batch
            'images' => [
                'nullable',
                'array',
                'max:20', // Maksimal 20 gambar sekaligus
            ],

            'images.*' => [
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120', // 5MB per gambar
                'dimensions:min_width=200,min_height=200,max_width=2000,max_height=2000',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            // Gallery ID messages
            'gallery_id.required' => 'Gallery ID wajib diisi.',
            'gallery_id.string' => 'Gallery ID harus berupa string.',
            'gallery_id.exists' => 'Gallery tidak ditemukan.',

            // Gambar messages
            'gambar.required' => 'Gambar wajib diupload.',
            'gambar.image' => 'File yang diupload harus berupa gambar.',
            'gambar.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, WebP.',
            'gambar.max' => 'Ukuran gambar maksimal 5MB.',
            'gambar.dimensions' => 'Dimensi gambar minimal 200x200px dan maksimal 2000x2000px.',

            // Urutan messages
            'urutan.integer' => 'Urutan harus berupa angka.',
            'urutan.min' => 'Urutan minimal 1.',
            'urutan.max' => 'Urutan maksimal 999.',

            // Multiple images messages
            'images.array' => 'Gambar harus berupa array.',
            'images.max' => 'Maksimal 20 gambar dapat diupload sekaligus.',
            'images.*.image' => 'Setiap file yang diupload harus berupa gambar.',
            'images.*.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, WebP.',
            'images.*.max' => 'Ukuran setiap gambar maksimal 5MB.',
            'images.*.dimensions' => 'Dimensi setiap gambar minimal 200x200px dan maksimal 2000x2000px.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'gallery_id' => 'gallery',
            'gambar' => 'gambar',
            'urutan' => 'urutan',
            'images' => 'gambar',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        // Log validation errors untuk debugging
        \Illuminate\Support\Facades\Log::warning('Gallery Image validation failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['gambar', 'images']), // Exclude file uploads dari log
        ]);

        parent::failedValidation($validator);
    }

    /**
     * Get validated data dengan transformasi yang diperlukan
     */
    public function getValidatedData(): array
    {
        $validated = $this->validated();

        // Set default urutan jika tidak diisi
        if (!isset($validated['urutan']) && isset($validated['gallery_id'])) {
            $validated['urutan'] = $this->getNextUrutan($validated['gallery_id']);
        }

        return $validated;
    }

    /**
     * Get next urutan untuk gallery tertentu
     */
    private function getNextUrutan(string $galleryId): int
    {
        return \App\Models\GalleryImage::where('gallery_id', $galleryId)
            ->max('urutan') + 1;
    }
}