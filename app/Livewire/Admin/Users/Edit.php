<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

/**
 * Komponen Livewire untuk mengedit user yang sudah ada
 * 
 * Fitur:
 * - Pre-filled form dengan data user
 * - Form validation lengkap
 * - Optional password change
 * - Role selection dengan proteksi admin
 * - Status aktif dan diizinkan
 * - Toast notification
 */
class Edit extends Component
{
    use Toast;

    // User yang sedang diedit
    public ?User $user = null;

    // Form Properties
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user';
    public bool $aktif = true;
    public bool $diizinkan = false;

    // Modal State
    public bool $showModal = false;

    // Password Change State
    public bool $changePassword = false;

    /**
     * Validation rules untuk form edit user
     */
    protected function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user?->id)
            ],
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'user'])
            ],
            'aktif' => 'boolean',
            'diizinkan' => 'boolean'
        ];

        // Tambahkan validasi password jika user ingin mengubah password
        if ($this->changePassword) {
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ];
            $rules['password_confirmation'] = 'required|same:password';
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.min' => 'Nama lengkap minimal 2 karakter.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'name.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar dalam sistem.',

            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',

            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password harus sama dengan password.',

            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.'
        ];
    }

    /**
     * Real-time validation untuk field tertentu
     */
    public function updated($propertyName)
    {
        // Validasi real-time untuk field yang diubah
        $this->validateOnly($propertyName);
    }

    /**
     * Toggle perubahan password
     */
    public function togglePasswordChange(): void
    {
        $this->changePassword = !$this->changePassword;

        // Reset password fields jika tidak ingin mengubah password
        if (!$this->changePassword) {
            $this->password = '';
            $this->password_confirmation = '';
            $this->resetValidation(['password', 'password_confirmation']);
        }
    }

    /**
     * Buka modal edit user
     */
    public function openModal(User $user): void
    {
        $this->user = $user;
        $this->loadUserData();
        $this->showModal = true;
    }

    /**
     * Load data user ke form
     */
    private function loadUserData(): void
    {
        if ($this->user) {
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->role = $this->user->role;
            $this->aktif = $this->user->aktif;
            $this->diizinkan = $this->user->diizinkan;
        }
    }

    /**
     * Tutup modal edit user
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    /**
     * Reset form ke nilai default
     */
    public function resetForm(): void
    {
        $this->user = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->aktif = true;
        $this->diizinkan = false;
        $this->changePassword = false;
    }

    /**
     * Cek apakah user yang sedang diedit adalah admin terakhir
     */
    private function isLastAdmin(): bool
    {
        if (!$this->user || $this->user->role !== 'admin') {
            return false;
        }

        $adminCount = User::where('role', 'admin')
            ->where('aktif', true)
            ->count();

        return $adminCount <= 1;
    }

    /**
     * Update user di database
     */
    public function update(): void
    {
        // Validasi semua input
        $validated = $this->validate();

        try {
            // Cek proteksi admin terakhir
            if ($this->isLastAdmin() && ($validated['role'] !== 'admin' || !$validated['aktif'])) {
                $this->error(
                    'Tidak dapat mengubah admin terakhir!',
                    'Sistem harus memiliki minimal satu admin aktif.',
                    position: 'toast-top toast-end'
                );
                return;
            }

            // Siapkan data untuk update
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'aktif' => $validated['aktif'],
                'diizinkan' => $validated['diizinkan'],
            ];

            // Tambahkan password jika diubah
            if ($this->changePassword && !empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Update user
            $this->user->update($updateData);

            // Tampilkan toast success
            $this->success('User berhasil diperbarui!');

            // Dispatch event untuk refresh parent component
            $this->dispatch('user-updated');

            // Reset form
            $this->reset();
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error updating user: ' . $e->getMessage(), [
                'user_id' => $this->user?->id,
                'user_data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            // Tampilkan toast error
            $this->error(
                'Gagal memperbarui user!',
                'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                position: 'toast-top toast-end'
            );
        }
    }

    /**
     * Render komponen
     */
    public function render()
    {
        return view('livewire.admin.users.edit');
    }
}
