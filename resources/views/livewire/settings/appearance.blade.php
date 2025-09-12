<div class="space-y-6">
    <!-- Theme Settings Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-base-content">Pengaturan Tampilan</h1>
        <p class="text-base-content/70">Sesuaikan tema aplikasi sesuai preferensi Anda</p>
    </div>

    <!-- Theme Selection Card -->
    <x-mary-card>
        <x-slot:title class="text-lg font-semibold">Pilih Tema</x-slot:title>

        <div class="space-y-4">

            <!-- Alternative: Manual Radio Buttons with MaryUI styling -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <!-- Light Theme -->
                <label
                    class="card bg-base-100 border cursor-pointer hover:bg-base-200 transition-all {{ $appearance === 'light' ? 'border-primary bg-primary/5' : 'border-base-300' }}">
                    <div class="card-body items-center text-center p-6">
                        <input type="radio" name="appearance" value="light" wire:model.live="appearance"
                            class="radio radio-primary mb-3" />
                        <div class="mb-3">
                            <x-mary-icon name="o-sun" class="w-12 h-12 text-yellow-500" />
                        </div>
                        <h4 class="font-semibold text-base-content">Light Mode</h4>
                        <p class="text-sm text-base-content/70">Tampilan terang</p>
                    </div>
                </label>

                <!-- Dark Theme -->
                <label
                    class="card bg-base-100 border cursor-pointer hover:bg-base-200 transition-all {{ $appearance === 'dark' ? 'border-primary bg-primary/5' : 'border-base-300' }}">
                    <div class="card-body items-center text-center p-6">
                        <input type="radio" name="appearance" value="dark" wire:model.live="appearance"
                            class="radio radio-primary mb-3" />
                        <div class="mb-3">
                            <x-mary-icon name="o-moon" class="w-12 h-12 text-slate-600" />
                        </div>
                        <h4 class="font-semibold text-base-content">Dark Mode</h4>
                        <p class="text-sm text-base-content/70">Tampilan gelap</p>
                    </div>
                </label>

                <!-- System Theme -->
                <label
                    class="card bg-base-100 border cursor-pointer hover:bg-base-200 transition-all {{ $appearance === 'system' ? 'border-primary bg-primary/5' : 'border-base-300' }}">
                    <div class="card-body items-center text-center p-6">
                        <input type="radio" name="appearance" value="system" wire:model.live="appearance"
                            class="radio radio-primary mb-3" />
                        <div class="mb-3">
                            <x-mary-icon name="o-computer-desktop" class="w-12 h-12 text-blue-600" />
                        </div>
                        <h4 class="font-semibold text-base-content">System</h4>
                        <p class="text-sm text-base-content/70">Ikuti sistem</p>
                    </div>
                </label>
            </div>

            <!-- Current Selection Info -->
            <x-mary-alert class="mt-6" icon="o-information-circle">
                <x-slot:title>Tema Aktif</x-slot:title>
                Tema saat ini: <strong class="capitalize">{{ $appearance }}</strong>
                @if ($appearance === 'system')
                    (mengikuti pengaturan sistem)
                @endif
            </x-mary-alert>

            <!-- Success Message -->
            @if (session('status') === 'appearance-updated')
                <x-mary-alert class="alert-success mt-4" icon="o-check-circle">
                    <x-slot:title>Berhasil!</x-slot:title>
                    Pengaturan tema berhasil diperbarui!
                </x-mary-alert>
            @endif
        </div>
    </x-mary-card>

    <!-- Information Card -->
    <x-mary-card>
        <x-slot:title class="text-lg font-semibold">Informasi Tema</x-slot:title>

        <div class="space-y-3 text-sm text-base-content/70">
            <div class="flex items-start gap-2">
                <x-mary-icon name="o-sun" class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" />
                <span><strong>Light Mode:</strong> Cocok untuk penggunaan di tempat terang dan siang hari</span>
            </div>
            <div class="flex items-start gap-2">
                <x-mary-icon name="o-moon" class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" />
                <span><strong>Dark Mode:</strong> Mengurangi kelelahan mata dan menghemat baterai</span>
            </div>
            <div class="flex items-start gap-2">
                <x-mary-icon name="o-computer-desktop" class="w-4 h-4 text-primary mt-0.5 flex-shrink-0" />
                <span><strong>System:</strong> Otomatis mengikuti pengaturan tema sistem operasi</span>
            </div>
        </div>
    </x-mary-card>
</div>
