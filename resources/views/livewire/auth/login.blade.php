<?php

use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    // Komponen ini hanya menampilkan tombol SSO Google
// Semua logic authentication ditangani oleh GoogleController
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Masuk ke Akun Anda')" :description="__('Gunakan akun Google untuk masuk ke sistem')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <!-- Error Message -->
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-center">
            {{ session('error') }}
        </div>
    @endif

    <!-- Success Message -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-center">
            {{ session('success') }}
        </div>
    @endif

    <!-- Google SSO Button -->
    <div class="space-y-4">
        <a href="{{ route('auth.google') }}"
            class="w-full flex items-center justify-center gap-3 px-6 py-3 border border-gray-300 rounded-lg shadow-sm bg-white hover:bg-gray-50 transition-colors duration-200 text-gray-700 font-medium">
            <!-- Google Icon SVG -->
            <svg class="w-5 h-5" viewBox="0 0 24 24">
                <path fill="#4285F4"
                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853"
                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05"
                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                <path fill="#EA4335"
                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            {{ __('Masuk dengan Google') }}
        </a>

        <!-- Info Text -->
        <div class="text-center text-sm text-gray-600 dark:text-gray-400">
            <p class="mb-2">{{ __('Sistem menggunakan Single Sign-On (SSO) Google') }}</p>
            <p class="text-xs">
                {{ __('Admin:') }} <span class="font-medium">ahmad.ritonga@mhs.unsoed.ac.id</span><br>
                {{ __('User:') }} <span class="font-medium">alriansr@gmail.com</span>
            </p>
        </div>
    </div>
</div>
