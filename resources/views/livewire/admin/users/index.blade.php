<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="title text-2xl font-bold text-base-content">Manajemen User</h1>
            <p class="body text-base-content/70 mt-1">Kelola user, role, dan akses sistem</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-2">
            <x-mary-button label="Tambah User" icon="o-plus" class="btn-primary" wire:click="openCreateModal" />
            <x-mary-button label="Reset Filter" icon="o-arrow-path" class="btn-ghost" wire:click="resetFilters" />
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-base-200 rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <x-mary-input label="Cari User" placeholder="Nama atau email..." icon="o-magnifying-glass"
                    wire:model.live.debounce.300ms="search" />
            </div>

            <!-- Role Filter -->
            <div>
                <x-mary-select label="Filter Role" :options="[
                    ['id' => 'all', 'name' => 'Semua Role'],
                    ['id' => 'admin', 'name' => 'Admin'],
                    ['id' => 'user', 'name' => 'User'],
                ]" option-value="id" option-label="name"
                    wire:model.live="roleFilter" />
            </div>

            <!-- Status Filter -->
            <div>
                <x-mary-select label="Filter Status" :options="[
                    ['id' => 'all', 'name' => 'Semua Status'],
                    ['id' => 'active', 'name' => 'Aktif'],
                    ['id' => 'inactive', 'name' => 'Tidak Aktif'],
                    ['id' => 'pending', 'name' => 'Menunggu Persetujuan'],
                ]" option-value="id" option-label="name"
                    wire:model.live="statusFilter" />
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-base-100 rounded-lg shadow-sm border border-base-300">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th class="font-semibold">User</th>
                        <th class="font-semibold">Role</th>
                        <th class="font-semibold">Status</th>
                        <th class="font-semibold">Login Terakhir</th>
                        <th class="font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="hover:bg-base-50">
                            <!-- User Info -->
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                            @if ($user->avatar)
                                                <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->nama }}"
                                                    class="w-10 h-10 rounded-full object-cover">
                                            @else
                                                <span
                                                    class="text-primary font-medium text-sm">{{ $user->initials() }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-base-content">{{ $user->nama }}</div>
                                        <div class="text-sm text-base-content/70">{{ $user->email }}</div>
                                        @if ($user->telepon)
                                            <div class="text-xs text-base-content/50">{{ $user->telepon }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td>
                                <div class="flex items-center gap-2">
                                    @if ($user->getKey() === Auth::id())
                                        <!-- Current user cannot change their own role -->
                                        <x-mary-badge :value="ucfirst($user->role)" :class="$user->role === 'admin' ? 'badge-error' : 'badge-info'" />
                                        <span class="text-xs text-base-content/50">(Anda)</span>
                                    @else
                                        <x-mary-select :options="[
                                            ['id' => 'admin', 'name' => 'Admin'],
                                            ['id' => 'user', 'name' => 'User'],
                                        ]" option-value="id" option-label="name"
                                            :value="$user->role"
                                            wire:change="confirmChangeRole({{ $user->getKey() }}, $event.target.value)"
                                            class="select-sm w-24" />
                                    @endif
                                </div>
                            </td>

                            <!-- Status -->
                            <td>
                                <div class="flex flex-col gap-1">
                                    <!-- Status Aktif -->
                                    <div class="flex items-center gap-2">
                                        @if ($user->getKey() === Auth::id())
                                            <x-mary-badge :value="$user->aktif ? 'Aktif' : 'Tidak Aktif'" :class="$user->aktif ? 'badge-success' : 'badge-error'" />
                                        @else
                                            <x-mary-toggle :checked="$user->aktif"
                                                wire:click="confirmToggleActive({{ $user->getKey() }})"
                                                class="toggle-sm {{ $user->aktif ? 'toggle-success' : 'toggle-error' }}" />
                                            <span
                                                class="text-sm {{ $user->aktif ? 'text-success' : 'text-error' }}">{{ $user->aktif ? 'Aktif' : 'Tidak Aktif' }}</span>
                                        @endif
                                    </div>

                                    <!-- Status Diizinkan -->
                                    <div class="flex items-center gap-2">
                                        <x-mary-toggle :checked="$user->diizinkan"
                                            wire:click="confirmToggleAllowed({{ $user->getKey() }})"
                                            class="toggle-sm {{ $user->diizinkan ? 'toggle-warning' : 'toggle-error' }}" />
                                        <span
                                            class="text-xs {{ $user->diizinkan ? 'text-warning' : 'text-error' }}">{{ $user->diizinkan ? 'Diizinkan' : 'Menunggu' }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Login Terakhir -->
                            <td>
                                @if ($user->login_terakhir)
                                    <div class="text-sm text-base-content">
                                        {{ $user->login_terakhir->format('d/m/Y H:i') }}
                                    </div>
                                    <div class="text-xs text-base-content/50">
                                        {{ $user->login_terakhir->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="text-sm text-base-content/50">Belum pernah login</span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td>
                                <div class="flex justify-center gap-1">
                                    <x-mary-button icon="o-pencil" class="btn-ghost btn-sm text-info hover:bg-info/10"
                                        wire:click="openEditModal({{ $user->getKey() }})" tooltip="Edit User" />
                                    @if ($user->getKey() !== Auth::id())
                                        <x-mary-button icon="o-trash"
                                            class="btn-ghost btn-sm text-error hover:bg-error/10"
                                            wire:click="confirmDelete({{ $user->getKey() }})" tooltip="Hapus User" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8">
                                <div class="flex flex-col items-center gap-2">
                                    <x-mary-icon name="o-users" class="w-12 h-12 text-base-content/30" />
                                    <p class="text-base-content/50">Tidak ada user ditemukan</p>
                                    @if ($search || $roleFilter !== 'all' || $statusFilter !== 'all')
                                        <x-mary-button label="Reset Filter" class="btn-sm btn-ghost"
                                            wire:click="resetFilters" />
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="p-4 border-t border-base-300">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus User">
        @if ($userToDelete)
            <div class="py-4">
                <div class="flex items-center gap-3 mb-4">
                    <x-mary-icon name="o-exclamation-triangle" class="w-8 h-8 text-warning" />
                    <div>
                        <h3 class="font-semibold text-base-content">Hapus User: {{ $userToDelete->nama }}</h3>
                        <p class="text-sm text-base-content/70">{{ $userToDelete->email }}</p>
                    </div>
                </div>

                <div class="bg-warning/10 border border-warning/20 rounded-lg p-3 mb-4">
                    <p class="text-sm text-warning-content">
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan.
                        Semua data yang terkait dengan user ini akan ikut terhapus.
                    </p>
                </div>

                <p class="text-base-content/70">
                    Apakah Anda yakin ingin menghapus user ini?
                </p>
            </div>

            <x-slot:actions>
                <x-mary-button label="Batal" class="btn-ghost" wire:click="closeDeleteModal" />
                <x-mary-button label="Hapus User" class="btn-error" icon="o-trash" wire:click="deleteUser"
                    spinner="deleteUser" />
            </x-slot:actions>
        @endif
    </x-mary-modal>

    <!-- Role Change Confirmation Modal -->
    <x-mary-modal wire:model="showRoleChangeModal" title="Konfirmasi Ubah Role">
        @if ($userToChangeRole)
            <div class="py-4">
                <div class="flex items-center gap-3 mb-4">
                    <x-mary-icon name="o-user-group" class="w-8 h-8 text-info" />
                    <div>
                        <h3 class="font-semibold text-base-content">Ubah Role: {{ $userToChangeRole->nama }}</h3>
                        <p class="text-sm text-base-content/70">{{ $userToChangeRole->email }}</p>
                    </div>
                </div>

                <div class="bg-info/10 border border-info/20 rounded-lg p-3 mb-4">
                    <p class="text-sm text-info-content">
                        <strong>Perubahan Role:</strong>
                        <span class="capitalize">{{ $userToChangeRole->role }}</span>
                        →
                        <span class="capitalize font-semibold">{{ $newRole }}</span>
                    </p>
                </div>

                <p class="text-base-content/70">
                    Apakah Anda yakin ingin mengubah role user ini?
                </p>
            </div>

            <x-slot:actions>
                <x-mary-button label="Batal" class="btn-ghost" wire:click="closeRoleChangeModal" />
                <x-mary-button label="Ubah Role" class="btn-info" icon="o-user-group" wire:click="changeRole"
                    spinner="changeRole" />
            </x-slot:actions>
        @endif
    </x-mary-modal>

    <!-- Status Change Confirmation Modal -->
    <x-mary-modal wire:model="showStatusChangeModal" title="Konfirmasi Ubah Status">
        @if ($userToChangeStatus)
            <div class="py-4">
                <div class="flex items-center gap-3 mb-4">
                    <x-mary-icon name="o-shield-check" class="w-8 h-8 text-warning" />
                    <div>
                        <h3 class="font-semibold text-base-content">Ubah Status: {{ $userToChangeStatus->nama }}</h3>
                        <p class="text-sm text-base-content/70">{{ $userToChangeStatus->email }}</p>
                    </div>
                </div>

                <div class="bg-warning/10 border border-warning/20 rounded-lg p-3 mb-4">
                    <p class="text-sm text-warning-content">
                        @if ($statusChangeType === 'active')
                            <strong>Status Aktif:</strong>
                            {{ $userToChangeStatus->aktif ? 'Aktif' : 'Tidak Aktif' }}
                            →
                            <span
                                class="font-semibold">{{ $userToChangeStatus->aktif ? 'Tidak Aktif' : 'Aktif' }}</span>
                        @elseif($statusChangeType === 'allowed')
                            <strong>Status Izin:</strong>
                            {{ $userToChangeStatus->diizinkan ? 'Diizinkan' : 'Menunggu Persetujuan' }}
                            →
                            <span
                                class="font-semibold">{{ $userToChangeStatus->diizinkan ? 'Menunggu Persetujuan' : 'Diizinkan' }}</span>
                        @endif
                    </p>
                </div>

                <p class="text-base-content/70">
                    Apakah Anda yakin ingin mengubah status user ini?
                </p>
            </div>

            <x-slot:actions>
                <x-mary-button label="Batal" class="btn-ghost" wire:click="closeStatusChangeModal" />
                <x-mary-button :label="$statusChangeType === 'active' ? 'Ubah Status Aktif' : 'Ubah Status Izin'" class="btn-warning" icon="o-shield-check"
                    wire:click="{{ $statusChangeType === 'active' ? 'toggleActive' : 'toggleAllowed' }}"
                    :spinner="$statusChangeType === 'active' ? 'toggleActive' : 'toggleAllowed'" />
            </x-slot:actions>
        @endif
    </x-mary-modal>

    <!-- Create User Modal -->
    <x-mary-modal wire:model="showCreateModal" title="Tambah User Baru" class="backdrop-blur">
        @if ($showCreateModal)
            <livewire:admin.users.create wire:key="create-user-{{ now() }}" @user-created="userCreated" />
        @endif
    </x-mary-modal>

    <!-- Edit User Modal -->
    <x-mary-modal wire:model="showEditModal" title="Edit User" class="backdrop-blur">
        @if ($showEditModal && $userToEdit)
            <livewire:admin.users.edit :user="$userToEdit"
                wire:key="edit-user-{{ $userToEdit->id }}-{{ now() }}" @user-updated="userUpdated" />
        @endif
    </x-mary-modal>
</div>
