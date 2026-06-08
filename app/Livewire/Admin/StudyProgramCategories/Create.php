<?php

namespace App\Livewire\Admin\StudyProgramCategories;

use App\Models\StudyProgramCategory;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

/**
 * Komponen Livewire untuk membuat kategori program studi baru
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
    public string $color = '#3b82f6';
    public string $icon = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    /**
     * Available icons
     */
    public array $availableIcons = [
        'o-academic-cap' => 'Academic Cap',
        'o-computer-desktop' => 'Computer Desktop',
        'o-paint-brush' => 'Paint Brush',
        'o-briefcase' => 'Briefcase',
        'o-wrench-screwdriver' => 'Wrench Screwdriver',
        'o-cog-6-tooth' => 'Cog',
        'o-beaker' => 'Beaker',
        'o-calculator' => 'Calculator',
        'o-camera' => 'Camera',
        'o-chart-bar' => 'Chart Bar',
        'o-globe-alt' => 'Globe',
        'o-musical-note' => 'Musical Note',
    ];

    /**
     * Available colors
     */
    public array $availableColors = [
        '#3b82f6' => 'Blue',
        '#ef4444' => 'Red',
        '#10b981' => 'Green',
        '#f59e0b' => 'Amber',
        '#8b5cf6' => 'Purple',
        '#ec4899' => 'Pink',
        '#06b6d4' => 'Cyan',
        '#84cc16' => 'Lime',
        '#f97316' => 'Orange',
        '#6366f1' => 'Indigo',
    ];

    /**
     * Validation rules
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:study_program_categories,name',
            'slug' => 'required|string|max:255|unique:study_program_categories,slug',
            'description' => 'nullable|string|max:500',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/i',
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

        while (StudyProgramCategory::where('slug', $this->slug)->exists()) {
            $this->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
    }

    /**
     * Generate slug manual
     */
    public function generateSlug(): void
    {
        $this->slug = Str::slug($this->name);

        // Pastikan slug unik
        $originalSlug = $this->slug;
        $counter = 1;

        while (StudyProgramCategory::where('slug', $this->slug)->exists()) {
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
        $this->sort_order = (StudyProgramCategory::max('sort_order') ?? 0) + 1;
    }

    /**
     * Save kategori
     */
    public function save(): void
    {
        $this->validate();

        try {
            StudyProgramCategory::create([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'color' => $this->color,
                'icon' => $this->icon ?: null,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            $this->success('Kategori program studi berhasil dibuat!');

            $this->redirect(route('admin.study-program-categories.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error('Gagal membuat kategori: ' . $e->getMessage());
        }
    }

    /**
     * Cancel dan kembali ke index
     */
    public function cancel(): void
    {
        $this->redirect(route('admin.study-program-categories.index'), navigate: true);
    }

    /**
     * Render component
     */
    public function render()
    {
        return view('livewire.admin.study-program-categories.create');
    }
}
