<div>
    <div class="max-w-3xl mx-auto">
        @if ($success)
            <x-mary-alert class="mb-6 bg-green-50 text-green-700 border-green-200">
                <x-slot:icon>
                    <x-mary-icon name="o-check-circle" class="w-5 h-5" />
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
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama <span
                            class="text-red-500">*</span></label>
                    <x-mary-input wire:model="nama" id="nama" type="text" class="mt-1 block w-full"
                        placeholder="Masukkan nama lengkap" />
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span
                            class="text-red-500">*</span></label>
                    <x-mary-input wire:model="email" id="email" type="email" class="mt-1 block w-full"
                        placeholder="Masukkan alamat email" />
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Telepon -->
            <div>
                <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">Telepon (Opsional)</label>
                <x-mary-input wire:model="telepon" id="telepon" type="tel" class="mt-1 block w-full"
                    placeholder="Masukkan nomor telepon" />
                @error('telepon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subjek -->
            <div>
                <label for="subjek" class="block text-sm font-medium text-gray-700 mb-1">Subjek <span
                        class="text-red-500">*</span></label>
                <x-mary-input wire:model="subjek" id="subjek" type="text" class="mt-1 block w-full"
                    placeholder="Masukkan subjek pesan" />
                @error('subjek')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Pesan -->
            <div>
                <label for="pesan" class="block text-sm font-medium text-gray-700 mb-1">Pesan <span
                        class="text-red-500">*</span></label>
                <x-mary-textarea wire:model="pesan" id="pesan" rows="6" class="mt-1 block w-full"
                    placeholder="Tulis pesan Anda di sini..."></x-mary-textarea>
                @error('pesan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <x-mary-button type="submit" class="px-6">
                    <span wire:loading.remove wire:target="submit">
                        <x-mary-icon name="o-paper-airplane" class="w-5 h-5 mr-2" />
                        Kirim Pesan
                    </span>
                    <span wire:loading wire:target="submit">
                        <x-mary-icon name="o-arrow-path" class="w-5 h-5 mr-2 animate-spin" />
                        Mengirim...
                    </span>
                </x-mary-button>
            </div>
        </form>
    </div>
</div>
