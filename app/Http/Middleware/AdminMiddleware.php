<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

/**
 * Middleware untuk memastikan hanya admin yang bisa mengakses admin panel
 * Melakukan pengecekan role dan status user
 */
class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Periksa apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Anda harus login terlebih dahulu.');
        }

        $user = Auth::user();

        // Periksa apakah user adalah admin
        if ($user->role !== 'admin') {
            // Logout user yang bukan admin
            Auth::logout();
            
            return redirect()->route('login')
                ->with('error', 'Akses ditolak. Hanya admin yang diizinkan mengakses area ini.');
        }

        // Periksa apakah admin aktif dan diizinkan
        if (!$user->aktif || !$user->diizinkan) {
            // Logout admin yang tidak aktif
            Auth::logout();
            
            return redirect()->route('login')
                ->with('error', 'Akun admin Anda tidak aktif atau belum disetujui.');
        }

        return $next($request);
    }
}