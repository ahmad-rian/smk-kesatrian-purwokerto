<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Dashboard - SMK Kesatrian')]
class Dashboard extends Component
{
    // Properties untuk statistik dashboard
    public int $totalSiswa = 0;
    public int $totalGuru = 0;
    public int $totalKelas = 0;
    public int $totalMapel = 0;

    public function mount(): void
    {
        // Simulate data loading - replace with actual database queries
        $this->loadDashboardStats();
    }

    public function loadDashboardStats(): void
    {
        // Dalam implementasi nyata, gunakan query database
        // contoh: $this->totalSiswa = User::where('role', 'siswa')->count();
        $this->totalSiswa = 1234;
        $this->totalGuru = 89;
        $this->totalKelas = 36;
        $this->totalMapel = 24;
    }

    /**
     * Refresh dashboard data
     */
    public function refreshData(): void
    {
        $this->loadDashboardStats();

        // Dispatch event untuk update UI
        $this->dispatch('dashboard-refreshed');

        // Flash message
        session()->flash('status', 'Data dashboard berhasil diperbarui!');
    }

    /**
     * Quick actions methods
     */
    public function goToAddStudent(): void
    {
        // Redirect to add student page (akan dibuat nanti)
        $this->redirect(route('admin.dashboard'), navigate: true);
    }

    public function goToCreateClass(): void
    {
        // Redirect to create class page (akan dibuat nanti)
        $this->redirect(route('admin.dashboard'), navigate: true);
    }

    public function goToReports(): void
    {
        // Redirect to reports page (akan dibuat nanti)
        $this->redirect(route('admin.dashboard'), navigate: true);
    }

    public function goToSettings(): void
    {
        $this->redirect(route('admin.settings.profile'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('livewire.admin.layout');
    }
}
