<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

/**
 * Livewire Component untuk Edit Berita
 * 
 * Fitur:
 * - Form edit berita dengan validasi
 * - Upload dan preview gambar
 * - Auto generate slug dari judul
 * - Update gambar dengan hapus gambar lama
 * - Redirect setelah update
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use WithFileUploads, Toast;

    // Properties untuk form
    public $newsId;
    public $judul = '';
    public $slug = '';
    public $konten = '';
    public $status = 'aktif';
    public $gambar;
    public $gambarLama = '';
    public $gambarPreview = '';

    // Validation rules
    protected function rules()
    {
        return [
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug,' . $this->newsId,
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

    // Custom validation messages
    protected $messages = [
        'judul.required' => 'Judul berita wajib diisi.',
        'judul.max' => 'Judul berita maksimal 255 karakter.',
        'slug.required' => 'Slug URL wajib diisi.',
        'slug.unique' => 'Slug URL sudah digunakan.',
        'konten.required' => 'Konten berita wajib diisi.',
        'status.required' => 'Status publikasi wajib dipilih.',
        'status.in' => 'Status publikasi tidak valid.',
        'gambar.image' => 'File harus berupa gambar.',
        'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        'gambar.dimensions' => 'Dimensi gambar minimal 300x200 pixel.'
    ];

    /**
     * Mount component dengan data berita
     */
    public function mount($id)
    {
        $news = News::findOrFail($id);
        
        $this->newsId = $news->id;
        $this->judul = $news->judul;
        $this->slug = $news->slug;
        $this->konten = $news->konten;
        $this->status = $news->status === 'published' ? 'aktif' : 'nonaktif';
        $this->gambarLama = $news->gambar;
        
        // Set preview gambar lama jika ada
        if ($this->gambarLama) {
            $this->gambarPreview = Storage::url($this->gambarLama);
        }
    }

    /**
     * Auto generate slug ketika judul berubah
     */
    public function updatedJudul($value)
    {
        $this->slug = Str::slug($value);
    }

    /**
     * Update preview gambar ketika file dipilih
     */
    public function updatedGambar()
    {
        if ($this->gambar) {
            $this->gambarPreview = $this->gambar->temporaryUrl();
        }
    }

    /**
     * Hapus gambar yang dipilih
     */
    public function removeGambar()
    {
        $this->gambar = null;
        $this->gambarPreview = $this->gambarLama ? Storage::url($this->gambarLama) : '';
    }

    /**
     * Update berita
     */
    public function save(): void
    {
        // Validasi input
        $this->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:news,slug,' . $this->newsId,
            'konten' => 'required|string',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => [
                'nullable',
                'image',
                'max:2048',
                'dimensions:min_width=300,min_height=200'
            ]
        ]);

        try {
            $news = News::findOrFail($this->newsId);
            
            // Prepare data update
            $data = [
                'judul' => $this->judul,
                'slug' => $this->slug,
                'konten' => $this->konten,
                'status' => $this->status === 'aktif' ? 'published' : 'draft',
                'updated_at' => now()
            ];

            // Handle upload gambar baru
            if ($this->gambar) {
                // Hapus gambar lama jika ada
                if ($news->gambar && Storage::exists($news->gambar)) {
                    Storage::delete($news->gambar);
                }
                
                // Upload gambar baru
                $filename = time() . '_' . $this->gambar->getClientOriginalName();
                $path = $this->gambar->storeAs('news', $filename, 'public');
                $data['gambar'] = $path;
            }

            // Update berita
            $news->update($data);

            $this->success('Berita berhasil diperbarui!');
            
            $this->redirect(route('admin.news.index'), navigate: true);
            
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batal edit dan kembali ke index
     */
    public function cancel()
    {
        $this->redirect(route('admin.news.index'), navigate: true);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.news.edit');
    }
}