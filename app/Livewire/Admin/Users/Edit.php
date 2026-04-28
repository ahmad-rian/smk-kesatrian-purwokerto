<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class Edit extends Component
{
    use Toast;

    // User yang sedang diedit
    public ?User $user = null;

    // Form Properties
    public string $nama = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user';
    public bool $aktif = true;
    public bool $diizinkan = false;

    // Password Change State
    public bool $changePassword = false;

    /**
     * Mount component with user data
     */
    public function mount(?User $user = null): void
    {
        if ($user) {
            $this->user = $user;
            $this->loadUserData();
        }
    }

    /**
     * Validation rules untuk form edit user
     */
    protected function rules(): array
    {
        $rules = [
            'nama' => [
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
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal 2 karakter.',
            'nama.max' => 'Nama lengkap maksimal 255 karakter.',
            'nama.regex' => 'Nama lengkap hanya boleh berisi huruf dan spasi.',

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
        $this->validateOnly($propertyName);
    }

    /**
     * Toggle perubahan password
     */
    public function togglePasswordChange(): void
    {
        $this->changePassword = !$this->changePassword;

        if (!$this->changePassword) {
            $this->password = '';
            $this->password_confirmation = '';
            $this->resetValidation(['password', 'password_confirmation']);
        }
    }

    /**
     * Load data user ke form
     */
    private function loadUserData(): void
    {
        if ($this->user) {
            $this->nama = $this->user->nama ?? '';
            $this->email = $this->user->email ?? '';
            $this->role = $this->user->role ?? 'user';
            $this->aktif = $this->user->aktif;
            $this->diizinkan = $this->user->diizinkan;
        }
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

            $updateData = [
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'aktif' => $validated['aktif'],
                'diizinkan' => $validated['diizinkan'],
            ];

            // Password is auto-hashed by the User model's 'hashed' cast
            if ($this->changePassword && !empty($validated['password'])) {
                $updateData['password'] = $validated['password'];
            }

            $this->user->update($updateData);

            $this->dispatch('user-updated');

            $this->success(
                'User berhasil diperbarui!',
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), [
                'user_id' => $this->user?->id,
                'trace' => $e->getTraceAsString()
            ]);

            $this->error(
                'Gagal memperbarui user!',
                'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                position: 'toast-top toast-end'
            );
        }
    }

    public function render()
    {
        return view('livewire.admin.users.edit');
    }
}
