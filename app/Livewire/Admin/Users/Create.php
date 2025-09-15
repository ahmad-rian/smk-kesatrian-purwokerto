<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Mary\Traits\Toast;

/**
 * Komponen Livewire untuk membuat user baru
 * 
 * Fitur:
 * - Form validation lengkap
 * - Password confirmation
 * - Role selection
 * - Status aktif dan diizinkan
 * - Toast notification
 */
class Create extends Component
{
    use Toast;

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

    /**
     * Validation rules untuk form create user
     */
    protected function rules(): array
    {
        return [
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

        // Auto-generate email dari nama jika email kosong
        if ($propertyName === 'name' && empty($this->email)) {
            $this->generateEmailFromName();
        }
    }

    /**
     * Generate email suggestion dari nama
     */
    private function generateEmailFromName(): void
    {
        if (!empty($this->name)) {
            $cleanName = strtolower(str_replace(' ', '.', trim($this->name)));
            $this->email = $cleanName . '@smkkesatrian.sch.id';
        }
    }

    /**
     * Buka modal create user
     */
    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    /**
     * Tutup modal create user
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
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'user';
        $this->aktif = true;
        $this->diizinkan = false;
    }

    /**
     * Simpan user baru ke database
     */
    public function store(): void
    {
        // Validasi semua input
        $validated = $this->validate();

        try {
            // Buat user baru
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'aktif' => $validated['aktif'],
                'diizinkan' => $validated['diizinkan'],
                'email_verified_at' => now(), // Auto verify untuk admin created users
            ]);

            // Tutup modal dan reset form
            $this->closeModal();

            // Emit event untuk refresh parent component
            $this->dispatch('user-created', [
                'user' => $user->toArray()
            ]);

            // Tampilkan toast success
            $this->success(
                'User berhasil dibuat!',
                'User ' . $user->name . ' telah ditambahkan ke sistem.',
                position: 'toast-top toast-end'
            );

            // Dispatch event untuk refresh parent component
            $this->dispatch('user-created');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Error creating user: ' . $e->getMessage(), [
                'user_data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);

            // Tampilkan toast error
            $this->error(
                'Gagal membuat user!',
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
        return view('livewire.admin.users.create');
    }
}
