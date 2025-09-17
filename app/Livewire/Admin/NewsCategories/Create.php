<?php

namespace App\Livewire\Admin\NewsCategories;

use App\Models\NewsCategory;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

/**
 * Komponen Livewire untuk membuat kategori berita baru
 * 
 * Fitur:
 * - Form input kategori lengkap
 * - Auto generate slug dari nama
 * - Validasi form
 * - Preview warna dan ikon
 */
#[Layout('livewire.admin.layout')]
class Create extends Component
{
    use Toast;

    /**
     * Form properties
     */
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $color = '#3B82F6';
    public string $icon = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    /**
     * Available icons - using basic icons
     */
    public array $availableIcons = [
        'o-home' => 'Home',
        'o-user' => 'User',
        'o-cog' => 'Settings',
        'o-star' => 'Star',
        'o-heart' => 'Heart',
        'o-eye' => 'Eye',
        'o-folder' => 'Folder',
        'o-document' => 'Document',
        'o-check' => 'Check',
        'o-plus' => 'Plus',
        'o-bell' => 'Bell',
        'o-envelope' => 'Mail',
    ];

    /**
     * Available colors
     */
    public array $availableColors = [
        '#3B82F6' => 'Blue',
        '#10B981' => 'Green',
        '#F59E0B' => 'Amber',
        '#8B5CF6' => 'Purple',
        '#EF4444' => 'Red',
        '#6366F1' => 'Indigo',
        '#EC4899' => 'Pink',
        '#14B8A6' => 'Teal',
        '#F97316' => 'Orange',
        '#84CC16' => 'Lime',
    ];

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:news_categories,name',
            'slug' => 'required|string|max:255|unique:news_categories,slug',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ];
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Nama kategori sudah digunakan.',
            'slug.required' => 'Slug wajib diisi.',
            'slug.unique' => 'Slug sudah digunakan.',
            'color.required' => 'Warna wajib dipilih.',
            'color.regex' => 'Format warna tidak valid.',
            'description.max' => 'Deskripsi maksimal 500 karakter.',
            'sort_order.min' => 'Urutan tidak boleh negatif.'
        ];
    }

    /**
     * Auto generate slug saat nama berubah
     */
    public function updatedName(): void
    {
        $this->slug = Str::slug($this->name);

        // Pastikan slug unik
        $originalSlug = $this->slug;
        $counter = 1;

        while (NewsCategory::where('slug', $this->slug)->exists()) {
            $this->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
    }

    /**
     * Mount component dengan nilai default
     */
    public function mount(): void
    {
        // Set default sort order
        $this->sort_order = NewsCategory::max('sort_order') + 1;
    }

    /**
     * Save kategori
     */
    public function save(): void
    {
        $this->validate();

        try {
            NewsCategory::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'color' => $this->color,
                'icon' => $this->icon ?: null,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            $this->success('Kategori berhasil dibuat!');

            $this->redirect(route('admin.news-categories.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error('Gagal membuat kategori: ' . $e->getMessage());
        }
    }

    /**
     * Cancel dan kembali ke index
     */
    public function cancel(): void
    {
        $this->redirect(route('admin.news-categories.index'), navigate: true);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.news-categories.create');
    }
}
