<?php

namespace App\Livewire\Admin\SiteSettings;

use Livewire\Component;
use App\Models\SiteSetting;
use Mary\Traits\Toast;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

/**
 * Livewire Component untuk halaman utama Site Settings
 * 
 * Menampilkan informasi pengaturan situs yang sudah ada
 * dan menyediakan navigasi ke halaman create/edit
 * 
 * @author Laravel Expert Agent
 * @version 1.0
 */
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use Toast;

    /**
     * Data pengaturan situs saat ini
     */
    public ?SiteSetting $siteSetting = null;

    /**
     * Mount component dan load data pengaturan
     */
    public function mount(): void
    {
        $this->loadSiteSetting();
    }

    /**
     * Load pengaturan situs dari database
     * Mengambil record pertama karena hanya ada satu pengaturan
     */
    private function loadSiteSetting(): void
    {
        $this->siteSetting = SiteSetting::first();
    }

    /**
     * Navigasi ke halaman create pengaturan baru
     */
    public function create(): void
    {
        $this->redirect(route('admin.site-settings.create'), navigate: true);
    }

    /**
     * Navigasi ke halaman edit pengaturan
     */
    public function edit(): void
    {
        if (!$this->siteSetting) {
            $this->error('Pengaturan tidak ditemukan');
            return;
        }

        $this->redirect(route('admin.site-settings.edit', $this->siteSetting->id), navigate: true);
    }

    /**
     * Render component
     */
    public function render(): View
    {
        return view('livewire.admin.site-settings.index');
    }
}