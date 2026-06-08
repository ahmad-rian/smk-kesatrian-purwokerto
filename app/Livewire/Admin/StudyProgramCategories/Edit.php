<?php

namespace App\Livewire\Admin\StudyProgramCategories;

use App\Models\StudyProgramCategory;
use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

/**
 * Komponen Livewire untuk mengedit kategori program studi
 */
#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use Toast;

    public StudyProgramCategory $studyProgramCategory;

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
     * Konfirmasi delete
     */
    public bool $showDeleteModal = false;

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
            'name' => 'required|string|max:255|unique:study_program_categories,name,' . $this->studyProgramCategory->id,
            'slug' => 'required|string|max:255|unique:study_program_categories,slug,' . $this->studyProgramCategory->id,
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
     * Mount component with existing data
     */
    public function mount(StudyProgramCategory $studyProgramCategory): void
    {
        $this->studyProgramCategory = $studyProgramCategory;
        $this->name = $this->studyProgramCategory->name;
        $this->slug = $this->studyProgramCategory->slug;
        $this->description = $this->studyProgramCategory->description ?? '';
        $this->color = $this->studyProgramCategory->color;
        $this->icon = $this->studyProgramCategory->icon ?? '';
        $this->is_active = $this->studyProgramCategory->is_active;
        $this->sort_order = $this->studyProgramCategory->sort_order;
    }

    /**
     * Auto generate slug saat nama berubah (jika slug belum diubah manual)
     */
    public function updatedName(): void
    {
        if ($this->slug === Str::slug($this->studyProgramCategory->name)) {
            $this->slug = Str::slug($this->name);
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

        while (StudyProgramCategory::where('slug', $this->slug)
            ->where('id', '!=', $this->studyProgramCategory->id)
            ->exists()) {
            $this->slug = $originalSlug . '-' . $counter;
            $counter++;
        }
    }

    /**
     * Update kategori
     */
    public function update(): void
    {
        $this->validate();

        try {
            $this->studyProgramCategory->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description ?: null,
                'color' => $this->color,
                'icon' => $this->icon ?: null,
                'is_active' => $this->is_active,
                'sort_order' => $this->sort_order,
            ]);

            $this->success('Kategori program studi berhasil diperbarui!');

            $this->redirect(route('admin.study-program-categories.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error('Gagal memperbarui kategori: ' . $e->getMessage());
        }
    }

    /**
     * Konfirmasi delete kategori
     */
    public function confirmDelete(): void
    {
        $this->showDeleteModal = true;
    }

    /**
     * Cancel delete
     */
    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
    }

    /**
     * Delete kategori
     */
    public function delete(): void
    {
        try {
            // Check if category has study programs
            if ($this->studyProgramCategory->studyPrograms()->count() > 0) {
                $this->error('Tidak dapat menghapus kategori yang masih memiliki program studi!');
                $this->cancelDelete();
                return;
            }

            $this->studyProgramCategory->delete();

            $this->success('Kategori berhasil dihapus!');

            $this->redirect(route('admin.study-program-categories.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error('Gagal menghapus kategori: ' . $e->getMessage());
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
        return view('livewire.admin.study-program-categories.edit');
    }
}
