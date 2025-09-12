<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

/**
 * Form Request untuk validasi data Gallery
 * 
 * Menangani validasi untuk operasi create dan update Gallery
 * dengan aturan yang berbeda berdasarkan konteks operasi
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
class GalleryRequest extends FormRequest
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
        // Get gallery ID from route parameter
        $galleryParam = $this->route()->parameter('gallery');
        $galleryId = null;
        
        if ($galleryParam) {
            if (is_numeric($galleryParam)) {
                $galleryId = $galleryParam;
            } elseif (is_object($galleryParam) && method_exists($galleryParam, 'getKey')) {
                $galleryId = $galleryParam->getKey();
            }
        }
        
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH') || !is_null($galleryId);

        return [
            // Judul gallery - wajib, string, maksimal 255 karakter, unik
            'judul' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('galleries', 'judul')->ignore($galleryId),
            ],

            // Slug - opsional saat input (auto-generate), string, maksimal 255 karakter, unik
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', // Format slug yang valid
                Rule::unique('galleries', 'slug')->ignore($galleryId),
            ],

            // Deskripsi - opsional, string, maksimal 1000 karakter
            'deskripsi' => [
                'nullable',
                'string',
                'max:1000',
            ],

            // Gambar sampul - wajib saat create, opsional saat update
            'gambar_sampul' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:5120', // 5MB maksimal
                'dimensions:min_width=300,min_height=200,max_width=2000,max_height=2000',
            ],

            // Status aktif - boolean
            'is_active' => [
                'boolean',
            ],

            // Tanggal publikasi - opsional, format tanggal yang valid
            'tanggal_publikasi' => [
                'nullable',
                'date',
                'after_or_equal:2020-01-01',
                'before_or_equal:' . now()->addYear()->format('Y-m-d'),
            ],

            // Meta title untuk SEO - opsional, string, maksimal 60 karakter
            'meta_title' => [
                'nullable',
                'string',
                'max:60',
            ],

            // Meta description untuk SEO - opsional, string, maksimal 160 karakter
            'meta_description' => [
                'nullable',
                'string',
                'max:160',
            ],

            // Gambar gallery (untuk upload multiple) - array gambar
            'gallery_images' => [
                'nullable',
                'array',
                'max:20', // Maksimal 20 gambar sekaligus
            ],

            'gallery_images.*' => [
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
            // Judul messages
            'judul.required' => 'Judul gallery wajib diisi.',
            'judul.string' => 'Judul gallery harus berupa teks.',
            'judul.min' => 'Judul gallery minimal 3 karakter.',
            'judul.max' => 'Judul gallery maksimal 255 karakter.',
            'judul.unique' => 'Judul gallery sudah digunakan, silakan gunakan judul lain.',

            // Slug messages
            'slug.string' => 'Slug harus berupa teks.',
            'slug.max' => 'Slug maksimal 255 karakter.',
            'slug.regex' => 'Format slug tidak valid. Gunakan huruf kecil, angka, dan tanda hubung.',
            'slug.unique' => 'Slug sudah digunakan, silakan gunakan slug lain.',

            // Deskripsi messages
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',

            // Gambar sampul messages
            'gambar_sampul.required' => 'Gambar sampul wajib diupload.',
            'gambar_sampul.image' => 'File yang diupload harus berupa gambar.',
            'gambar_sampul.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, WebP.',
            'gambar_sampul.max' => 'Ukuran gambar sampul maksimal 5MB.',
            'gambar_sampul.dimensions' => 'Dimensi gambar sampul minimal 300x200px dan maksimal 2000x2000px.',

            // Status messages
            'is_active.boolean' => 'Status aktif harus berupa true atau false.',

            // Tanggal publikasi messages
            'tanggal_publikasi.date' => 'Format tanggal publikasi tidak valid.',
            'tanggal_publikasi.after_or_equal' => 'Tanggal publikasi tidak boleh sebelum tahun 2020.',
            'tanggal_publikasi.before_or_equal' => 'Tanggal publikasi tidak boleh lebih dari 1 tahun ke depan.',

            // Meta title messages
            'meta_title.string' => 'Meta title harus berupa teks.',
            'meta_title.max' => 'Meta title maksimal 60 karakter untuk SEO optimal.',

            // Meta description messages
            'meta_description.string' => 'Meta description harus berupa teks.',
            'meta_description.max' => 'Meta description maksimal 160 karakter untuk SEO optimal.',

            // Gallery images messages
            'gallery_images.array' => 'Gambar gallery harus berupa array.',
            'gallery_images.max' => 'Maksimal 20 gambar dapat diupload sekaligus.',
            'gallery_images.*.image' => 'Setiap file yang diupload harus berupa gambar.',
            'gallery_images.*.mimes' => 'Format gambar yang diizinkan: JPEG, JPG, PNG, WebP.',
            'gallery_images.*.max' => 'Ukuran setiap gambar maksimal 5MB.',
            'gallery_images.*.dimensions' => 'Dimensi setiap gambar minimal 200x200px dan maksimal 2000x2000px.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'judul' => 'judul gallery',
            'slug' => 'slug',
            'deskripsi' => 'deskripsi',
            'gambar_sampul' => 'gambar sampul',
            'is_active' => 'status aktif',
            'tanggal_publikasi' => 'tanggal publikasi',
            'meta_title' => 'meta title',
            'meta_description' => 'meta description',
            'gallery_images' => 'gambar gallery',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-generate slug jika tidak diisi
        if (!$this->filled('slug') && $this->filled('judul')) {
            $this->merge([
                'slug' => Str::slug($this->judul),
            ]);
        }

        // Set default value untuk is_active jika tidak diisi
        if (!$this->has('is_active')) {
            $this->merge([
                'is_active' => true,
            ]);
        }

        // Set tanggal publikasi ke hari ini jika tidak diisi
        if (!$this->filled('tanggal_publikasi')) {
            $this->merge([
                'tanggal_publikasi' => now()->format('Y-m-d'),
            ]);
        }

        // Generate meta title dari judul jika tidak diisi
        if (!$this->filled('meta_title') && $this->filled('judul')) {
            $this->merge([
                'meta_title' => Str::limit($this->judul, 57), // 60 - 3 untuk "..."
            ]);
        }

        // Generate meta description dari deskripsi jika tidak diisi
        if (!$this->filled('meta_description') && $this->filled('deskripsi')) {
            $this->merge([
                'meta_description' => Str::limit(strip_tags($this->deskripsi), 157), // 160 - 3 untuk "..."
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        // Log validation errors untuk debugging
        \Illuminate\Support\Facades\Log::warning('Gallery validation failed', [
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['gambar_sampul', 'gallery_images']), // Exclude file uploads dari log
            'user_id' => auth()->id(),
            'ip' => $this->ip(),
        ]);

        parent::failedValidation($validator);
    }

    /**
     * Get validated data dengan transformasi yang diperlukan
     */
    public function getValidatedData(): array
    {
        $validated = $this->validated();

        // Transform boolean values
        if (isset($validated['is_active'])) {
            $validated['is_active'] = (bool) $validated['is_active'];
        }

        // Transform tanggal publikasi ke Carbon instance
        if (isset($validated['tanggal_publikasi'])) {
            $validated['tanggal_publikasi'] = \Carbon\Carbon::parse($validated['tanggal_publikasi']);
        }

        return $validated;
    }
}