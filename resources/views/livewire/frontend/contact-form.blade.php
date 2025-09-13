<div>
    <div class="max-w-3xl mx-auto">
        @if ($success)
            <x-mary-alert class="mb-6 bg-green-50 text-green-700 border-green-200">
                <x-slot:icon>
                    <x-mary-icon name="check-circle" class="w-5 h-5" />
                </x-slot:icon>
                <div class="text-lg font-medium">
                    Pesan Anda telah terkirim!
                </div>
                <p class="mt-2">
                    Terima kasih telah menghubungi kami. Tim kami akan segera merespons pesan Anda.
                </p>
            </x-mary-alert>
        @endif

        <form wire:submit="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <x-mary-label for="nama" value="Nama" required />
                    <x-mary-input wire:model="nama" id="nama" type="text" class="mt-1 block w-full" placeholder="Masukkan nama lengkap" />
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <x-mary-label for="email" value="Email" required />
                    <x-mary-input wire:model="email" id="email" type="email" class="mt-1 block w-full" placeholder="Masukkan alamat email" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Telepon -->
            <div>
                <x-mary-label for="telepon" value="Telepon (Opsional)" />
                <x-mary-input wire:model="telepon" id="telepon" type="tel" class="mt-1 block w-full" placeholder="Masukkan nomor telepon" />
                @error('telepon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subjek -->
            <div>
                <x-mary-label for="subjek" value="Subjek" required />
                <x-mary-input wire:model="subjek" id="subjek" type="text" class="mt-1 block w-full" placeholder="Masukkan subjek pesan" />
                @error('subjek')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pesan -->
            <div>
                <x-mary-label for="pesan" value="Pesan" required />
                <x-mary-textarea wire:model="pesan" id="pesan" rows="6" class="mt-1 block w-full" placeholder="Tulis pesan Anda di sini..."></x-mary-textarea>
                @error('pesan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <x-mary-button type="submit" class="px-6">
                    <span wire:loading.remove wire:target="submit">
                        <x-mary-icon name="paper-airplane" class="w-5 h-5 mr-2" />
                        Kirim Pesan
                    </span>
                    <span wire:loading wire:target="submit">
                        <x-mary-icon name="arrow-path" class="w-5 h-5 mr-2 animate-spin" />
                        Mengirim...
                    </span>
                </x-mary-button>
            </div>
        </form>
    </div>
</div>