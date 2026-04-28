<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    // Form Properties
    public string $nama = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $role = 'user';
    public bool $aktif = true;
    public bool $diizinkan = false;

    /**
     * Validation rules untuk form create user
     */
    protected function rules(): array
    {
        return [
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
                Rule::unique('users', 'email')
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'password_confirmation' => 'required|same:password',
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'user'])
            ],
            'aktif' => 'boolean',
            'diizinkan' => 'boolean'
        ];
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

        // Auto-generate email dari nama jika email kosong
        if ($propertyName === 'nama' && empty($this->email)) {
            $this->generateEmailFromName();
        }
    }

    /**
     * Generate email suggestion dari nama
     */
    private function generateEmailFromName(): void
    {
        if (!empty($this->nama)) {
            $cleanName = strtolower(str_replace(' ', '.', trim($this->nama)));
            $this->email = $cleanName . '@smkkesatrian.sch.id';
        }
    }

    /**
     * Reset form ke nilai default
     */
    public function resetForm(): void
    {
        $this->nama = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->aktif = true;
        $this->diizinkan = false;
        $this->resetValidation();
    }

    /**
     * Simpan user baru ke database
     */
    public function store(): void
    {
        $validated = $this->validate();

        try {
            // Password is auto-hashed by the User model's 'hashed' cast
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'aktif' => $validated['aktif'],
                'diizinkan' => $validated['diizinkan'],
                'email_verified_at' => now(),
            ]);

            $this->resetForm();

            $this->dispatch('user-created');

            $this->success(
                'User berhasil dibuat!',
                'User ' . $user->nama . ' telah ditambahkan ke sistem.',
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            $this->error(
                'Gagal membuat user!',
                'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.',
                position: 'toast-top toast-end'
            );
        }
    }

    public function render()
    {
        return view('livewire.admin.users.create');
    }
}
