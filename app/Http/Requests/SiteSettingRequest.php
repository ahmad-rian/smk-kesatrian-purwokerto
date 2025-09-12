<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request untuk validasi data Site Setting
 * 
 * Menangani validasi input untuk semua field pada tabel site_settings
 * dengan aturan yang sesuai untuk setiap jenis data
 */
class SiteSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Akan diatur sesuai dengan policy jika diperlukan
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Informasi Dasar Sekolah
            'nama_sekolah' => [
                'required',
                'string',
                'max:255',
                'min:3'
            ],
            'nama_singkat' => [
                'nullable',
                'string',
                'max:50'
            ],
            'tahun_berdiri' => [
                'nullable',
                'integer',
                'min:1900',
                'max:' . date('Y')
            ],
            'tagline' => [
                'nullable',
                'string',
                'max:500'
            ],
            'deskripsi' => [
                'nullable',
                'string',
                'max:2000'
            ],

            // Logo dan Foto
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // 2MB
                'dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000'
            ],
            'foto_kepala_sekolah' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // 2MB
                'dimensions:min_width=200,min_height=250,max_width=1500,max_height=2000'
            ],
            'nama_kepala_sekolah' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s.,\'-]+$/u' // Hanya huruf, spasi, dan karakter nama umum
            ],

            // Kontak dan Alamat
            'alamat' => [
                'required',
                'string',
                'max:1000',
                'min:10'
            ],
            'telepon' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\s\-\(\)]+$/' // Format telepon fleksibel
            ],
            'email' => [
                'nullable',
                'email:rfc',
                'max:255'
            ],
            'website' => [
                'nullable',
                'url',
                'max:255',
                'regex:/^https?:\/\/.+/' // Harus dimulai dengan http:// atau https://
            ],

            // Media Sosial (akan divalidasi secara terpisah)
            'instagram' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->isValidSocialMedia($value, 'instagram')) {
                        $fail('Format Instagram tidak valid. Gunakan @username atau URL lengkap.');
                    }
                }
            ],
            'facebook' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->isValidSocialMedia($value, 'facebook')) {
                        $fail('Format Facebook tidak valid. Gunakan nama halaman atau URL lengkap.');
                    }
                }
            ],
            'youtube' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->isValidSocialMedia($value, 'youtube')) {
                        $fail('Format YouTube tidak valid. Gunakan nama channel atau URL lengkap.');
                    }
                }
            ],
            'tiktok' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if ($value && !$this->isValidSocialMedia($value, 'tiktok')) {
                        $fail('Format TikTok tidak valid. Gunakan @username atau URL lengkap.');
                    }
                }
            ],

            // Visi dan Misi
            'visi' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'misi' => [
                'nullable',
                'string',
                'max:2000'
            ],


        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Nama Sekolah
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'nama_sekolah.min' => 'Nama sekolah minimal 3 karakter.',
            'nama_sekolah.max' => 'Nama sekolah maksimal 255 karakter.',
            
            // Nama Singkat
            'nama_singkat.max' => 'Nama singkat maksimal 50 karakter.',
            
            // Tahun Berdiri
            'tahun_berdiri.integer' => 'Tahun berdiri harus berupa angka.',
            'tahun_berdiri.min' => 'Tahun berdiri tidak boleh kurang dari 1900.',
            'tahun_berdiri.max' => 'Tahun berdiri tidak boleh lebih dari tahun sekarang.',
            
            // Tagline
            'tagline.max' => 'Tagline maksimal 500 karakter.',
            
            // Deskripsi
            'deskripsi.max' => 'Deskripsi maksimal 2000 karakter.',
            
            // Logo
            'logo.image' => 'File logo harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPEG, JPG, PNG, atau WebP.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'logo.dimensions' => 'Dimensi logo minimal 100x100px dan maksimal 2000x2000px.',
            
            // Foto Kepala Sekolah
            'foto_kepala_sekolah.image' => 'File foto kepala sekolah harus berupa gambar.',
            'foto_kepala_sekolah.mimes' => 'Foto kepala sekolah harus berformat JPEG, JPG, PNG, atau WebP.',
            'foto_kepala_sekolah.max' => 'Ukuran foto kepala sekolah maksimal 2MB.',
            'foto_kepala_sekolah.dimensions' => 'Dimensi foto kepala sekolah minimal 200x250px dan maksimal 1500x2000px.',
            
            // Nama Kepala Sekolah
            'nama_kepala_sekolah.max' => 'Nama kepala sekolah maksimal 255 karakter.',
            'nama_kepala_sekolah.regex' => 'Nama kepala sekolah hanya boleh mengandung huruf, spasi, dan tanda baca umum.',
            
            // Alamat
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.min' => 'Alamat minimal 10 karakter.',
            'alamat.max' => 'Alamat maksimal 1000 karakter.',
            
            // Telepon
            'telepon.max' => 'Nomor telepon maksimal 20 karakter.',
            'telepon.regex' => 'Format nomor telepon tidak valid.',
            
            // Email
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 255 karakter.',
            
            // Website
            'website.url' => 'Format website tidak valid.',
            'website.max' => 'URL website maksimal 255 karakter.',
            'website.regex' => 'Website harus dimulai dengan http:// atau https://',
            
            // Visi
            'visi.max' => 'Visi maksimal 1000 karakter.',
            
            // Misi
            'misi.max' => 'Misi maksimal 2000 karakter.',
            
            // Status
            'is_active.boolean' => 'Status aktif harus berupa true atau false.'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_sekolah' => 'nama sekolah',
            'nama_singkat' => 'nama singkat',
            'tahun_berdiri' => 'tahun berdiri',
            'tagline' => 'tagline',
            'deskripsi' => 'deskripsi',
            'logo' => 'logo',
            'foto_kepala_sekolah' => 'foto kepala sekolah',
            'nama_kepala_sekolah' => 'nama kepala sekolah',
            'alamat' => 'alamat',
            'telepon' => 'telepon',
            'email' => 'email',
            'website' => 'website',
            'instagram' => 'Instagram',
            'facebook' => 'Facebook',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'visi' => 'visi',
            'misi' => 'misi',
            'is_active' => 'status aktif'
        ];
    }

    /**
     * Validasi format media sosial
     *
     * @param string $value
     * @param string $platform
     * @return bool
     */
    private function isValidSocialMedia(string $value, string $platform): bool
    {
        $patterns = [
            'instagram' => [
                '/^@[a-zA-Z0-9._]{1,30}$/', // @username format
                '/^https?:\/\/(www\.)?instagram\.com\/[a-zA-Z0-9._]{1,30}\/?$/' // URL format
            ],
            'facebook' => [
                '/^[a-zA-Z0-9.\s]{1,50}$/', // Page name format
                '/^https?:\/\/(www\.)?facebook\.com\/[a-zA-Z0-9.]{1,50}\/?$/' // URL format
            ],
            'youtube' => [
                '/^[a-zA-Z0-9\s]{1,50}$/', // Channel name format
                '/^https?:\/\/(www\.)?youtube\.com\/(channel\/|c\/|user\/)?[a-zA-Z0-9_-]{1,50}\/?$/' // URL format
            ],
            'tiktok' => [
                '/^@[a-zA-Z0-9._]{1,24}$/', // @username format
                '/^https?:\/\/(www\.)?tiktok\.com\/@[a-zA-Z0-9._]{1,24}\/?$/' // URL format
            ]
        ];

        if (!isset($patterns[$platform])) {
            return false;
        }

        foreach ($patterns[$platform] as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Bersihkan dan format data sebelum validasi
        $this->merge([
            'nama_sekolah' => $this->cleanString($this->nama_sekolah),
            'nama_singkat' => $this->cleanString($this->nama_singkat),
            'tagline' => $this->cleanString($this->tagline),
            'deskripsi' => $this->cleanString($this->deskripsi),
            'nama_kepala_sekolah' => $this->cleanString($this->nama_kepala_sekolah),
            'alamat' => $this->cleanString($this->alamat),
            'telepon' => $this->cleanString($this->telepon),
            'email' => $this->cleanEmail($this->email),
            'website' => $this->cleanUrl($this->website),
            'instagram' => $this->cleanString($this->instagram),
            'facebook' => $this->cleanString($this->facebook),
            'youtube' => $this->cleanString($this->youtube),
            'tiktok' => $this->cleanString($this->tiktok),
            'visi' => $this->cleanString($this->visi),
            'misi' => $this->cleanString($this->misi),
        ]);
    }

    /**
     * Bersihkan string dari karakter yang tidak diinginkan
     *
     * @param string|null $value
     * @return string|null
     */
    private function cleanString(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return trim(preg_replace('/\s+/', ' ', $value));
    }

    /**
     * Bersihkan dan format email
     *
     * @param string|null $value
     * @return string|null
     */
    private function cleanEmail(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return strtolower(trim($value));
    }

    /**
     * Bersihkan dan format URL
     *
     * @param string|null $value
     * @return string|null
     */
    private function cleanUrl(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);
        
        // Tambahkan https:// jika tidak ada protokol
        if (!preg_match('/^https?:\/\//', $value)) {
            $value = 'https://' . $value;
        }

        return $value;
    }
}