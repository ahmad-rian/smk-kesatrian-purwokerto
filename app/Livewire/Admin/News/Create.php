<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;

/**
 * Komponen Livewire untuk membuat berita baru
 * 
 * Fitur:
 * - Form input berita lengkap
 * - Upload gambar dengan preview
 * - Auto generate slug dari judul
 * - Validasi form
 */
class Create extends Component
{
    use WithFileUploads, Toast;

    /**
     * Form properties
     */
    public string $judul = '';
    public string $slug = '';
    public string $konten = '';
    public string $status = 'aktif';
    public $gambar;
    public ?string $gambarPreview = null;

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug',
            'konten' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => [
                'nullable',
                'image',
                'max:2048', // 2MB
                'dimensions:min_width=300,min_height=200'
            ]
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'judul.required' => 'Judul berita wajib diisi.',
            'judul.max' => 'Judul berita maksimal 255 karakter.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan, silakan gunakan yang lain.',
            'konten.required' => 'Konten berita wajib diisi.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status harus aktif atau nonaktif.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
            'gambar.dimensions' => 'Dimensi gambar minimal 300x200 pixel.'
        ];
    }

    /**
     * Auto generate slug saat judul berubah
     */
    public function updatedJudul(): void
    {
        $this->slug = Str::slug($this->judul);
        
        // Pastikan slug unik
        $originalSlug = $this->slug;
        $counter = 1;
        
        while (News::where('slug', $this->slug)->exists()) {
            $this->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
    }

    /**
     * Handle gambar upload dan preview
     */
    public function updatedGambar(): void
    {
        $this->validate([
            'gambar' => [
                'nullable',
                'image',
                'max:2048',
                'dimensions:min_width=300,min_height=200'
            ]
        ]);

        if ($this->gambar) {
            $this->gambarPreview = $this->gambar->temporaryUrl();
        }
    }

    /**
     * Remove gambar
     */
    public function removeGambar(): void
    {
        $this->gambar = null;
        $this->gambarPreview = null;
    }

    /**
     * Save berita
     */
    public function save(): void
    {
        $this->validate();

        try {
            $data = [
                'judul' => $this->judul,
                'slug' => $this->slug,
                'konten' => $this->konten,
                'status' => $this->status,
            ];

            // Upload gambar jika ada
            if ($this->gambar) {
                $filename = 'news/' . time() . '_' . Str::random(10) . '.' . $this->gambar->getClientOriginalExtension();
                $data['gambar'] = $this->gambar->storeAs('public', $filename);
                $data['gambar'] = str_replace('public/', '', $data['gambar']);
            }

            News::create($data);

            $this->success('Berita berhasil dibuat!');
            
            $this->redirect(route('admin.news.index'), navigate: true);
            
        } catch (\Exception $e) {
            $this->error('Gagal membuat berita: ' . $e->getMessage());
        }
    }

    /**
     * Cancel dan kembali ke index
     */
    public function cancel(): void
    {
        $this->redirect(route('admin.news.index'), navigate: true);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.news.create');
    }
}