{{-- Modal Edit User --}}
<div>
    {{-- Modal --}}
    <x-mary-modal wire:model="showModal" title="Edit User" subtitle="Perbarui informasi pengguna" class="backdrop-blur">

        @if ($user)
            {{-- User Info Header --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 mb-6 border border-blue-200">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <x-mary-icon name="o-user" class="w-6 h-6 text-blue-600" />
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-slate-900"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            {{ $user->name }}
                        </h4>
                        <p class="text-sm text-slate-600">{{ $user->email }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $user->aktif ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Edit User --}}
            <form wire:submit="update" class="space-y-6">

                {{-- Informasi Dasar --}}
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        <x-mary-icon name="o-user" class="w-5 h-5 mr-2 text-blue-600" />
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Nama Lengkap --}}
                        <div class="md:col-span-2">
                            <x-mary-input wire:model.live="name" label="Nama Lengkap"
                                placeholder="Masukkan nama lengkap pengguna" icon="o-user" class="input-bordered"
                                hint="Minimal 2 karakter, hanya huruf dan spasi" />
                        </div>

                        {{-- Email --}}
                        <div class="md:col-span-2">
                            <x-mary-input wire:model.live="email" label="Email" type="email"
                                placeholder="user@smkkesatrian.sch.id" icon="o-envelope" class="input-bordered"
                                hint="Email akan digunakan untuk login" />
                        </div>
                    </div>
                </div>

                {{-- Keamanan --}}
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900 flex items-center"
                            style="font-family: 'Bricolage Grotesque', sans-serif;">
                            <x-mary-icon name="o-lock-closed" class="w-5 h-5 mr-2 text-green-600" />
                            Keamanan
                        </h3>

                        {{-- Toggle Change Password --}}
                        <div class="flex items-center gap-2">
                            <x-mary-toggle wire:model.live="changePassword" wire:click="togglePasswordChange"
                                class="toggle-sm toggle-primary" />
                            <span class="text-sm font-medium text-slate-700">
                                {{ $changePassword ? 'Ubah Password' : 'Pertahankan Password' }}
                            </span>
                        </div>
                    </div>

                    @if ($changePassword)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- Password Baru --}}
                            <div>
                                <x-mary-input wire:model.live="password" label="Password Baru" type="password"
                                    placeholder="Masukkan password baru" icon="o-lock-closed" class="input-bordered"
                                    hint="Min 8 karakter, huruf besar, kecil, dan angka" />
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <x-mary-input wire:model.live="password_confirmation" label="Konfirmasi Password"
                                    type="password" placeholder="Ulangi password baru" icon="o-lock-closed"
                                    class="input-bordered" hint="Harus sama dengan password baru" />
                            </div>
                        </div>

                        {{-- Password Strength Indicator --}}
                        @if ($password)
                            <div class="mt-4 p-3 bg-white rounded-lg border">
                                <p class="text-sm font-medium text-slate-700 mb-2">Kekuatan Password:</p>
                                <div class="flex items-center gap-2">
                                    @php
                                        $hasLower = preg_match('/[a-z]/', $password);
                                        $hasUpper = preg_match('/[A-Z]/', $password);
                                        $hasNumber = preg_match('/\d/', $password);
                                        $hasMinLength = strlen($password) >= 8;
                                        $strength = $hasLower + $hasUpper + $hasNumber + $hasMinLength;
                                    @endphp

                                    <div class="flex-1 bg-slate-200 rounded-full h-2">
                                        <div
                                            class="h-2 rounded-full transition-all duration-300 
                                            @if ($strength <= 1) bg-red-500 w-1/4
                                            @elseif($strength == 2) bg-yellow-500 w-2/4
                                            @elseif($strength == 3) bg-blue-500 w-3/4
                                            @else bg-green-500 w-full @endif
                                        ">
                                        </div>
                                    </div>

                                    <span
                                        class="text-xs font-medium
                                        @if ($strength <= 1) text-red-600
                                        @elseif($strength == 2) text-yellow-600
                                        @elseif($strength == 3) text-blue-600
                                        @else text-green-600 @endif
                                    ">
                                        @if ($strength <= 1)
                                            Lemah
                                        @elseif($strength == 2)
                                            Sedang
                                        @elseif($strength == 3)
                                            Kuat
                                        @else
                                            Sangat Kuat
                                        @endif
                                    </span>
                                </div>

                                {{-- Requirements Checklist --}}
                                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                    <div
                                        class="flex items-center gap-1 {{ $hasMinLength ? 'text-green-600' : 'text-slate-500' }}">
                                        <x-mary-icon name="{{ $hasMinLength ? 'o-check-circle' : 'o-x-circle' }}"
                                            class="w-3 h-3" />
                                        Min 8 karakter
                                    </div>
                                    <div
                                        class="flex items-center gap-1 {{ $hasLower ? 'text-green-600' : 'text-slate-500' }}">
                                        <x-mary-icon name="{{ $hasLower ? 'o-check-circle' : 'o-x-circle' }}"
                                            class="w-3 h-3" />
                                        Huruf kecil
                                    </div>
                                    <div
                                        class="flex items-center gap-1 {{ $hasUpper ? 'text-green-600' : 'text-slate-500' }}">
                                        <x-mary-icon name="{{ $hasUpper ? 'o-check-circle' : 'o-x-circle' }}"
                                            class="w-3 h-3" />
                                        Huruf besar
                                    </div>
                                    <div
                                        class="flex items-center gap-1 {{ $hasNumber ? 'text-green-600' : 'text-slate-500' }}">
                                        <x-mary-icon name="{{ $hasNumber ? 'o-check-circle' : 'o-x-circle' }}"
                                            class="w-3 h-3" />
                                        Angka
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="p-4 bg-white rounded-lg border border-slate-200">
                            <div class="flex items-center gap-3 text-slate-600">
                                <x-mary-icon name="o-shield-check" class="w-5 h-5 text-green-600" />
                                <span class="text-sm">Password saat ini akan dipertahankan</span>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Pengaturan Akun --}}
                <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        <x-mary-icon name="o-cog-6-tooth" class="w-5 h-5 mr-2 text-purple-600" />
                        Pengaturan Akun
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        {{-- Role --}}
                        <div>
                            <x-mary-select wire:model.live="role" label="Role Pengguna" :options="[
                                ['id' => 'user', 'name' => 'User - Pengguna Biasa'],
                                ['id' => 'admin', 'name' => 'Admin - Administrator'],
                            ]"
                                option-value="id" option-label="name" placeholder="Pilih role pengguna"
                                class="select-bordered" hint="Tentukan level akses pengguna" />

                            {{-- Warning untuk admin terakhir --}}
                            @if ($user && $user->role === 'admin' && $role !== 'admin')
                                @php
                                    $adminCount = \App\Models\User::where('role', 'admin')
                                        ->where('aktif', true)
                                        ->count();
                                @endphp
                                @if ($adminCount <= 1)
                                    <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <div class="flex items-center gap-2 text-yellow-800">
                                            <x-mary-icon name="o-exclamation-triangle" class="w-4 h-4" />
                                            <span class="text-xs font-medium">Peringatan: Admin terakhir!</span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Status Aktif --}}
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 mb-2">Status Aktif</label>
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border">
                                <x-mary-toggle wire:model.live="aktif"
                                    class="toggle-sm {{ $aktif ? 'toggle-success' : 'toggle-error' }}" />
                                <div class="flex-1">
                                    <span
                                        class="text-sm font-medium {{ $aktif ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $aktif ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $aktif ? 'User dapat login ke sistem' : 'User tidak dapat login' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Status Diizinkan --}}
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-slate-700 mb-2">Status Izin</label>
                            <div class="flex items-center gap-3 p-3 bg-white rounded-lg border">
                                <x-mary-toggle wire:model.live="diizinkan"
                                    class="toggle-sm {{ $diizinkan ? 'toggle-warning' : 'toggle-error' }}" />
                                <div class="flex-1">
                                    <span
                                        class="text-sm font-medium {{ $diizinkan ? 'text-yellow-700' : 'text-red-700' }}">
                                        {{ $diizinkan ? 'Diizinkan' : 'Menunggu' }}
                                    </span>
                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $diizinkan ? 'User diizinkan mengakses' : 'Menunggu persetujuan' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        @endif

        {{-- Modal Actions --}}
        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="closeModal" class="btn-ghost" />

            <x-mary-button label="Perbarui User" wire:click="update" spinner="update" class="btn-primary"
                icon="o-pencil-square" />
        </x-slot:actions>

    </x-mary-modal>
</div>

{{-- Styles untuk animasi dan transisi --}}
<style>
    .toggle-sm {
        transition: all 0.3s ease;
    }

    .input-bordered:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .select-bordered:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>
