<?php

namespace App\Livewire\Admin\FrontendMenus;

use App\Models\FrontendMenu;
use App\Models\CustomPage;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Mary\Traits\Toast;
use Livewire\Attributes\Layout;

#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use Toast;

    public string $search = '';
    public string $statusFilter = 'all';

    public bool $showDeleteModal = false;
    public ?FrontendMenu $menuToDelete = null;

    public function toggleStatus(FrontendMenu $menu): void
    {
        $menu->update(['is_active' => !$menu->is_active]);
        $status = $menu->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->success("Menu berhasil {$status}!");
    }

    public function moveUp(FrontendMenu $menu): void
    {
        $previous = FrontendMenu::where('parent_id', $menu->parent_id)
            ->where('sort_order', '<', $menu->sort_order)
            ->orderByDesc('sort_order')
            ->first();

        if ($previous) {
            $tempOrder = $menu->sort_order;
            $menu->update(['sort_order' => $previous->sort_order]);
            $previous->update(['sort_order' => $tempOrder]);
            $this->success('Urutan menu diperbarui!');
        }
    }

    public function moveDown(FrontendMenu $menu): void
    {
        $next = FrontendMenu::where('parent_id', $menu->parent_id)
            ->where('sort_order', '>', $menu->sort_order)
            ->orderBy('sort_order')
            ->first();

        if ($next) {
            $tempOrder = $menu->sort_order;
            $menu->update(['sort_order' => $next->sort_order]);
            $next->update(['sort_order' => $tempOrder]);
            $this->success('Urutan menu diperbarui!');
        }
    }

    public function confirmDelete(FrontendMenu $menu): void
    {
        $this->menuToDelete = $menu;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->menuToDelete = null;
        $this->showDeleteModal = false;
    }

    public function deleteMenu(): void
    {
        if (!$this->menuToDelete) {
            return;
        }

        try {
            // Clean up associated custom page
            if ($this->menuToDelete->custom_page_id) {
                $page = CustomPage::find($this->menuToDelete->custom_page_id);
                if ($page) {
                    if ($page->featured_image) {
                        Storage::disk('public')->delete($page->featured_image);
                    }
                    $page->delete();
                }
            }

            $this->menuToDelete->delete();
            $this->success('Menu berhasil dihapus!');
            $this->cancelDelete();
        } catch (\Exception $e) {
            $this->error('Gagal menghapus menu: ' . $e->getMessage());
        }
    }

    public function getMenusProperty()
    {
        $query = FrontendMenu::topLevel()->with('activeChildren')->ordered();

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter === 'active') {
            $query->where('is_active', true);
        } elseif ($this->statusFilter === 'inactive') {
            $query->where('is_active', false);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.admin.frontend-menus.index', [
            'menus' => $this->menus,
        ]);
    }
}
