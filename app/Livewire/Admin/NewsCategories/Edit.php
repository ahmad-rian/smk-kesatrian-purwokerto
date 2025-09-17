<?php

namespace App\Livewire\Admin\NewsCategories;

use App\Models\NewsCategory;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

/**
 * Komponen Livewire untuk mengedit kategori berita
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use Toast;

    public NewsCategory $newsCategory;

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
     * Available icons and colors (same as Create)
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
            'name' => 'required|string|max:255|unique:news_categories,name,' . $this->newsCategory->id,
            'slug' => 'required|string|max:255|unique:news_categories,slug,' . $this->newsCategory->id,
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'icon' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0'
        ];
    }

    /**
     * Mount component with existing data
     */
    public function mount(): void
    {
        $this->name = $this->newsCategory->name;
        $this->slug = $this->newsCategory->slug;
        $this->description = $this->newsCategory->description ?? '';
        $this->color = $this->newsCategory->color;
        $this->icon = $this->newsCategory->icon ?? '';
        $this->is_active = $this->newsCategory->is_active;
        $this->sort_order = $this->newsCategory->sort_order;
    }

    /**
     * Auto generate slug saat nama berubah (jika slug belum diubah manual)
     */
    public function updatedName(): void
    {
        if ($this->slug === Str::slug($this->newsCategory->name)) {
            $this->slug = Str::slug($this->name);
        }
    }

    /**
     * Update kategori
     */
    public function save(): void
    {
        $this->validate();

        try {
            $this->newsCategory->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'color' => $this->color,
                'icon' => $this->icon ?: null,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            $this->success('Kategori berhasil diperbarui!');

            $this->redirect(route('admin.news-categories.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error('Gagal memperbarui kategori: ' . $e->getMessage());
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
        return view('livewire.admin.news-categories.edit');
    }
}
