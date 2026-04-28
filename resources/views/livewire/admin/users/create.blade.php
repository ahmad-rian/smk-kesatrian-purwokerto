{{-- Create User Form (rendered inside parent modal) --}}
<div>
    {{-- Form Create User --}}
    <form wire:submit="store" class="space-y-6">

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
                    <x-mary-input wire:model.live="nama" label="Nama Lengkap"
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

        {{-- Info: Login via Google --}}
        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
            <div class="flex items-center gap-3 text-blue-800">
                <x-mary-icon name="o-shield-check" class="w-5 h-5 text-blue-600" />
                <span class="text-sm font-medium">Login menggunakan Google. Password akan digenerate otomatis oleh sistem.</span>
            </div>
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
                    ]" option-value="id"
                        option-label="name" placeholder="Pilih role pengguna" class="select-bordered"
                        hint="Tentukan level akses pengguna" />
                </div>

                {{-- Status Aktif --}}
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-slate-700 mb-2">Status Aktif</label>
                    <div class="flex items-center gap-3 p-3 bg-white rounded-lg border">
                        <x-mary-toggle wire:model.live="aktif"
                            class="toggle-sm {{ $aktif ? 'toggle-success' : 'toggle-error' }}" />
                        <div class="flex-1">
                            <span class="text-sm font-medium {{ $aktif ? 'text-green-700' : 'text-red-700' }}">
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

        {{-- Form Actions --}}
        <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
            <x-mary-button label="Batal" wire:click="$parent.closeCreateModal" class="btn-ghost" />
            <x-mary-button label="Simpan User" type="submit" spinner="store" class="btn-primary" icon="o-plus" />
        </div>

    </form>
</div>
