<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect user ke Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google OAuth
     * HANYA ADMIN YANG DIIZINKAN LOGIN
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Periksa apakah email adalah admin
            $userRole = $this->determineUserRole($googleUser->getEmail());

            // PEMBATASAN: Hanya admin yang bisa login
            if ($userRole !== 'admin') {
                return redirect()->route('login')
                    ->with('error', 'Email Anda tidak memiliki akses untuk masuk ke sistem ini. Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.');
            }

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User sudah ada, update informasi login
                $user->update([
                    'login_terakhir' => now(),
                    'email_verified_at' => now(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Auto-registrasi user admin baru
                $user = User::create([
                    'id' => Str::ulid(), // Generate ULID untuk primary key
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(32)), // Password random untuk SSO
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'login_terakhir' => now(),
                    'role' => 'admin', // Pastikan role admin
                    'aktif' => true,
                    'diizinkan' => true, // Auto-approve untuk admin
                ]);
            }

            // Login user
            Auth::login($user, true);

            // Redirect berdasarkan role
            return $this->redirectBasedOnRole($user);
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }

    /**
     * ALTERNATIF: Handle callback untuk user biasa (DINONAKTIFKAN)
     * Uncomment method ini jika ingin mengizinkan user biasa login
     */
    /*
    public function handleGoogleCallbackForAllUsers()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();
            
            if ($user) {
                // User sudah ada, update informasi login
                $user->update([
                    'login_terakhir' => now(),
                    'email_verified_at' => now(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                // Auto-registrasi user baru (semua role)
                $user = User::create([
                    'id' => Str::ulid(),
                    'nama' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(32)),
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                    'login_terakhir' => now(),
                    'role' => $this->determineUserRole($googleUser->getEmail()),
                    'aktif' => true,
                    'diizinkan' => false, // Perlu approval admin untuk user biasa
                ]);
            }
            
            // Periksa apakah user diizinkan login
            if (!$user->isActiveAndAllowed() && $user->role !== 'admin') {
                return redirect()->route('login')
                    ->with('error', 'Akun Anda belum disetujui oleh admin. Silakan hubungi administrator.');
            }
            
            // Login user
            Auth::login($user, true);
            
            // Redirect berdasarkan role
            return $this->redirectBasedOnRole($user);
            
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
    */

    /**
     * Tentukan role user berdasarkan email
     */
    private function determineUserRole(string $email): string
    {
        // Email admin yang sudah ditentukan
        $adminEmails = [
            'ahmad.ritonga@mhs.unsoed.ac.id',
        ];

        return in_array($email, $adminEmails) ? 'admin' : 'user';
    }

    /**
     * Redirect user berdasarkan role setelah login
     */
    private function redirectBasedOnRole(User $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, Admin!');
        }

        return redirect()->route('dashboard')
            ->with('success', 'Login berhasil!');
    }
}
