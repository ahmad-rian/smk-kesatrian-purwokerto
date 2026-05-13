<?php

namespace App\Livewire\Admin\FrontendMenus;

use App\Models\FrontendMenu;
use App\Models\CustomPage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

#[Layout('livewire.admin.layout')]
class Edit extends Component
{
    use Toast, WithFileUploads;

    public FrontendMenu $frontendMenu;

    // Menu fields
    public string $title = '';
    public string $type = 'route';
    public string $route_name = '';
    public string $url = '';
    public string $icon = '';
    public ?int $parent_id = null;
    public bool $is_active = true;
    public bool $open_in_new_tab = false;
    public string $css_class = '';
    public int $sort_order = 0;

    // Custom Page fields
    public string $page_slug = '';
    public string $page_excerpt = '';
    public string $page_content = '';
    public string $page_meta_description = '';
    public $page_featured_image = null;
    public ?string $current_featured_image = null;
    public bool $page_show_hero = true;
    public string $page_hero_style = 'gradient';
    public bool $page_is_published = true;

    public array $availableIcons = [
        'o-home' => 'Home',
        'o-building-office-2' => 'Building',
        'o-calendar-days' => 'Calendar',
        'o-academic-cap' => 'Academic',
        'o-building-library' => 'Library',
        'o-newspaper' => 'Newspaper',
        'o-phone' => 'Phone',
        'o-envelope' => 'Mail',
        'o-photo' => 'Photo',
        'o-user' => 'User',
        'o-users' => 'Users',
        'o-cog-6-tooth' => 'Settings',
        'o-star' => 'Star',
        'o-heart' => 'Heart',
        'o-globe-alt' => 'Globe',
        'o-map-pin' => 'Map Pin',
        'o-document-text' => 'Document',
        'o-information-circle' => 'Info',
        'o-link' => 'Link',
        'o-book-open' => 'Book',
        'o-clipboard-document-list' => 'Clipboard',
        'o-chart-bar' => 'Chart',
        'o-shield-check' => 'Shield',
        'o-trophy' => 'Trophy',
        'o-sparkles' => 'Sparkles',
        'o-megaphone' => 'Megaphone',
        'o-video-camera' => 'Video',
        'o-musical-note' => 'Music',
        'o-puzzle-piece' => 'Puzzle',
        'o-wrench-screwdriver' => 'Tools',
    ];

    protected function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:route,url,page',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable|exists:frontend_menus,id',
            'is_active' => 'boolean',
            'open_in_new_tab' => 'boolean',
            'css_class' => 'nullable|string|max:255',
            'sort_order' => 'integer|min:0',
        ];

        if ($this->type === 'route') {
            $rules['route_name'] = 'required|string|max:255';
        } elseif ($this->type === 'url') {
            $rules['url'] = 'required|string|max:500';
        } elseif ($this->type === 'page') {
            $pageId = $this->frontendMenu->custom_page_id;
            $rules['page_slug'] = 'required|string|max:255|unique:custom_pages,slug,' . ($pageId ?? 0);
            $rules['page_content'] = 'required|string';
            $rules['page_excerpt'] = 'nullable|string|max:500';
            $rules['page_meta_description'] = 'nullable|string|max:255';
            $rules['page_featured_image'] = 'nullable|image|max:2048';
            $rules['page_show_hero'] = 'boolean';
            $rules['page_hero_style'] = 'required|in:gradient,image,simple';
            $rules['page_is_published'] = 'boolean';
        }

        return $rules;
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'Nama menu wajib diisi.',
            'route_name.required' => 'Route name wajib diisi untuk tipe Route.',
            'url.required' => 'URL wajib diisi untuk tipe Custom URL.',
            'page_slug.required' => 'Slug halaman wajib diisi.',
            'page_slug.unique' => 'Slug halaman sudah digunakan.',
            'page_content.required' => 'Konten halaman wajib diisi.',
            'page_featured_image.image' => 'File harus berupa gambar.',
            'page_featured_image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }

    public function mount(): void
    {
        $this->title = $this->frontendMenu->title;
        $this->icon = $this->frontendMenu->icon ?? '';
        $this->parent_id = $this->frontendMenu->parent_id;
        $this->is_active = $this->frontendMenu->is_active;
        $this->open_in_new_tab = $this->frontendMenu->open_in_new_tab;
        $this->css_class = $this->frontendMenu->css_class ?? '';
        $this->sort_order = $this->frontendMenu->sort_order;

        // Determine type
        if ($this->frontendMenu->custom_page_id) {
            $this->type = 'page';
            $page = $this->frontendMenu->customPage;
            if ($page) {
                $this->page_slug = $page->slug;
                $this->page_excerpt = $page->excerpt ?? '';
                $this->page_content = $page->content ?? '';
                $this->page_meta_description = $page->meta_description ?? '';
                $this->current_featured_image = $page->featured_image;
                $this->page_show_hero = $page->show_hero;
                $this->page_hero_style = $page->hero_style;
                $this->page_is_published = $page->is_published;
            }
        } elseif ($this->frontendMenu->route_name) {
            $this->type = 'route';
            $this->route_name = $this->frontendMenu->route_name;
        } else {
            $this->type = 'url';
            $this->url = $this->frontendMenu->url ?? '';
        }
    }

    public function getAvailableRoutesProperty(): array
    {
        return [
            'home' => 'Beranda (/)',
            'profil' => 'Profil Sekolah (/profil)',
            'kegiatan' => 'Kegiatan (/kegiatan)',
            'jurusan' => 'Jurusan (/jurusan)',
            'fasilitas.index' => 'Fasilitas (/fasilitas)',
            'berita' => 'Berita (/berita)',
            'kontak' => 'Kontak (/kontak)',
        ];
    }

    public function getParentMenusProperty()
    {
        return FrontendMenu::topLevel()
            ->where('id', '!=', $this->frontendMenu->id)
            ->ordered()
            ->get();
    }

    public function save(): void
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $customPageId = null;

                if ($this->type === 'page') {
                    $pageData = [
                        'title' => $this->title,
                        'slug' => $this->page_slug,
                        'excerpt' => $this->page_excerpt ?: null,
                        'content' => $this->page_content,
                        'meta_description' => $this->page_meta_description ?: null,
                        'show_hero' => $this->page_show_hero,
                        'hero_style' => $this->page_hero_style,
                        'is_published' => $this->page_is_published,
                    ];

                    if ($this->page_featured_image) {
                        // Delete old image
                        if ($this->current_featured_image) {
                            Storage::disk('public')->delete($this->current_featured_image);
                        }
                        $pageData['featured_image'] = $this->page_featured_image->store('custom-pages', 'public');
                    }

                    if ($this->frontendMenu->custom_page_id) {
                        $page = CustomPage::find($this->frontendMenu->custom_page_id);
                        $page->update($pageData);
                        $customPageId = $page->id;
                    } else {
                        $page = CustomPage::create($pageData);
                        $customPageId = $page->id;
                    }
                } else {
                    // If switching away from page type, clean up old custom page
                    if ($this->frontendMenu->custom_page_id) {
                        $oldPage = CustomPage::find($this->frontendMenu->custom_page_id);
                        if ($oldPage) {
                            if ($oldPage->featured_image) {
                                Storage::disk('public')->delete($oldPage->featured_image);
                            }
                            $oldPage->delete();
                        }
                    }
                }

                $this->frontendMenu->update([
                    'title' => $this->title,
                    'route_name' => $this->type === 'route' ? $this->route_name : null,
                    'url' => $this->type === 'url' ? $this->url : null,
                    'icon' => $this->icon ?: null,
                    'parent_id' => $this->parent_id,
                    'is_active' => $this->is_active,
                    'open_in_new_tab' => $this->open_in_new_tab,
                    'css_class' => $this->css_class ?: null,
                    'sort_order' => $this->sort_order,
                    'custom_page_id' => $customPageId,
                ]);
            });

            $this->success('Menu berhasil diperbarui!');
            $this->redirect(route('admin.frontend-menus.index'), navigate: true);
        } catch (\Exception $e) {
            $this->error('Gagal memperbarui menu: ' . $e->getMessage());
        }
    }

    public function cancel(): void
    {
        $this->redirect(route('admin.frontend-menus.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.frontend-menus.edit');
    }
}
