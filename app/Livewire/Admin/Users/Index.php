<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

/**
 * Komponen untuk menampilkan dan mengelola daftar user
 * Fitur: CRUD user, filter berdasarkan role dan status
 */
#[Title('Manajemen User - Admin Panel')]
#[Layout('livewire.admin.layout')]
class Index extends Component
{
    use WithPagination, Toast;

    // Properties untuk pencarian dan filter
    public string $search = '';
    public string $roleFilter = 'all';
    public string $statusFilter = 'all';
    public int $perPage = 10;

    // Properties untuk modal konfirmasi
    public bool $showDeleteModal = false;
    public ?User $userToDelete = null;

    // Properties untuk modal konfirmasi role change
    public bool $showRoleChangeModal = false;
    public ?User $userToChangeRole = null;
    public string $newRole = '';

    // Properties untuk modal konfirmasi status change
    public bool $showStatusChangeModal = false;
    public ?User $userToChangeStatus = null;
    public string $statusChangeType = ''; // 'active' atau 'allowed'

    // Properties untuk modal Create dan Edit User
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?User $userToEdit = null;

    /**
     * Reset pagination ketika search berubah
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination ketika filter berubah
     */
    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination ketika status filter berubah
     */
    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Get users dengan filter dan pencarian
     */
    public function getUsersProperty()
    {
        $query = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('nama', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter !== 'all', function ($query) {
                $query->where('role', $this->roleFilter);
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                if ($this->statusFilter === 'active') {
                    $query->where('aktif', true)->where('diizinkan', true);
                } elseif ($this->statusFilter === 'inactive') {
                    $query->where('aktif', false);
                } elseif ($this->statusFilter === 'pending') {
                    $query->where('diizinkan', false);
                }
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate($this->perPage);
    }

    /**
     * Konfirmasi toggle status aktif user
     */
    public function confirmToggleActive(User $user): void
    {
        // Cegah admin menonaktifkan dirinya sendiri
        if ($user->getKey() === Auth::id() && $user->aktif) {
            $this->error('Anda tidak dapat menonaktifkan akun Anda sendiri.');
            return;
        }

        $this->userToChangeStatus = $user;
        $this->statusChangeType = 'active';
        $this->showStatusChangeModal = true;
    }

    /**
     * Toggle status aktif user
     */
    public function toggleActive(): void
    {
        try {
            if (!$this->userToChangeStatus) {
                $this->error('User tidak ditemukan.');
                return;
            }

            $this->userToChangeStatus->update([
                'aktif' => !$this->userToChangeStatus->aktif
            ]);

            $status = $this->userToChangeStatus->aktif ? 'diaktifkan' : 'dinonaktifkan';
            $this->success("User {$this->userToChangeStatus->nama} berhasil {$status}.");

            $this->closeStatusChangeModal();
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat mengubah status user.');
        }
    }

    /**
     * Konfirmasi toggle status diizinkan user
     */
    public function confirmToggleAllowed(User $user): void
    {
        $this->userToChangeStatus = $user;
        $this->statusChangeType = 'allowed';
        $this->showStatusChangeModal = true;
    }

    /**
     * Toggle status diizinkan user
     */
    public function toggleAllowed(): void
    {
        try {
            if (!$this->userToChangeStatus) {
                $this->error('User tidak ditemukan.');
                return;
            }

            $this->userToChangeStatus->update([
                'diizinkan' => !$this->userToChangeStatus->diizinkan
            ]);

            $status = $this->userToChangeStatus->diizinkan ? 'diizinkan' : 'tidak diizinkan';
            $this->success("User {$this->userToChangeStatus->nama} berhasil {$status}.");

            $this->closeStatusChangeModal();
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat mengubah izin user.');
        }
    }

    /**
     * Konfirmasi ubah role user
     */
    public function confirmChangeRole(User $user, string $newRole): void
    {
        // Cegah admin mengubah role dirinya sendiri
        if ($user->getKey() === Auth::id()) {
            $this->error('Anda tidak dapat mengubah role Anda sendiri.');
            return;
        }

        $this->userToChangeRole = $user;
        $this->newRole = $newRole;
        $this->showRoleChangeModal = true;
    }

    /**
     * Ubah role user
     */
    public function changeRole(): void
    {
        try {
            if (!$this->userToChangeRole || !$this->newRole) {
                $this->error('Data tidak lengkap.');
                return;
            }

            $this->userToChangeRole->update([
                'role' => $this->newRole
            ]);

            $this->success("Role user {$this->userToChangeRole->nama} berhasil diubah menjadi {$this->newRole}.");

            $this->closeRoleChangeModal();
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat mengubah role user.');
        }
    }

    /**
     * Konfirmasi hapus user
     */
    public function confirmDelete(User $user): void
    {
        // Cegah admin menghapus dirinya sendiri
        if ($user->getKey() === Auth::id()) {
            $this->error('Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        $this->userToDelete = $user;
        $this->showDeleteModal = true;
    }

    /**
     * Hapus user
     */
    public function deleteUser(): void
    {
        try {
            if ($this->userToDelete) {
                $userName = $this->userToDelete->nama;
                $this->userToDelete->delete();

                $this->success("User {$userName} berhasil dihapus.");
                $this->closeDeleteModal();
            }
        } catch (\Exception $e) {
            $this->error('Terjadi kesalahan saat menghapus user.');
        }
    }

    /**
     * Tutup modal delete
     */
    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
    }

    /**
     * Tutup modal role change
     */
    public function closeRoleChangeModal(): void
    {
        $this->showRoleChangeModal = false;
        $this->userToChangeRole = null;
        $this->newRole = '';
    }

    /**
     * Tutup modal status change
     */
    public function closeStatusChangeModal(): void
    {
        $this->showStatusChangeModal = false;
        $this->userToChangeStatus = null;
        $this->statusChangeType = '';
    }

    /**
     * Reset semua filter
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->roleFilter = 'all';
        $this->statusFilter = 'all';
        $this->resetPage();
    }

    /**
     * Buka modal Create User
     */
    public function openCreateModal(): void
    {
        $this->showCreateModal = true;
    }

    /**
     * Tutup modal Create User
     */
    public function closeCreateModal(): void
    {
        $this->showCreateModal = false;
    }

    /**
     * Buka modal Edit User
     */
    public function openEditModal(User $user): void
    {
        $this->userToEdit = $user;
        $this->showEditModal = true;
    }

    /**
     * Tutup modal Edit User
     */
    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->userToEdit = null;
    }

    /**
     * Handle event ketika user berhasil dibuat
     */
    public function userCreated(): void
    {
        $this->closeCreateModal();
        $this->success('User berhasil dibuat!');
    }

    /**
     * Handle event ketika user berhasil diupdate
     */
    public function userUpdated(): void
    {
        $this->closeEditModal();
        $this->success('User berhasil diperbarui!');
    }

    /**
     * Render komponen
     */
    public function render(): View
    {
        return view('livewire.admin.users.index', [
            'users' => $this->users
        ]);
    }
}
