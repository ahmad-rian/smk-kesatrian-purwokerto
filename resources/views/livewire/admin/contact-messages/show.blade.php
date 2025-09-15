<div>
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            Detail Pesan Kontak
        </h1>
        <a href="{{ route('admin.contact-messages.index') }}" wire:navigate class="btn btn-outline">
            <x-mary-icon name="o-arrow-left" class="w-4 h-4 mr-2" />
            Kembali
        </a>
    </div>

    <!-- Flash Message -->
    @if (session('message'))
        <x-mary-alert class="mb-4">
            <x-slot:icon>
                <x-mary-icon name="o-check-circle" class="w-5 h-5" />
            </x-slot:icon>
            {{ session('message') }}
        </x-mary-alert>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informasi Pengirim -->
        <div class="md:col-span-1">
            <x-mary-card title="Informasi Pengirim">
                <div class="space-y-4">
                    <!-- Status Pesan -->
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-1">Status:</span>
                        @if ($message->isUnread())
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                <span class="w-2 h-2 mr-1 bg-blue-500 rounded-full"></span>
                                Belum Dibaca
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <span class="w-2 h-2 mr-1 bg-gray-500 rounded-full"></span>
                                Sudah Dibaca
                            </span>
                        @endif
                    </div>

                    <!-- Nama -->
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-1">Nama:</span>
                        <p class="text-base font-medium text-gray-900 dark:text-white">{{ $message->nama }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-1">Email:</span>
                        <p class="text-base text-gray-900 dark:text-white">
                            <a href="mailto:{{ $message->email }}"
                                class="text-blue-600 hover:underline dark:text-blue-400">
                                {{ $message->email }}
                            </a>
                        </p>
                    </div>

                    <!-- Telepon -->
                    @if ($message->telepon)
                        <div>
                            <span
                                class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-1">Telepon:</span>
                            <p class="text-base text-gray-900 dark:text-white">
                                <a href="tel:{{ $message->telepon }}"
                                    class="text-blue-600 hover:underline dark:text-blue-400">
                                    {{ $message->telepon }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <!-- Tanggal -->
                    <div>
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400 block mb-1">Tanggal:</span>
                        <p class="text-base text-gray-900 dark:text-white">
                            {{ $message->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <!-- Aksi -->
                    <div class="flex space-x-2 pt-4">
                        @if ($message->isRead())
                            <button wire:click="markAsUnread" class="btn btn-sm btn-secondary">
                                <x-mary-icon name="o-envelope" class="w-4 h-4 mr-2" />
                                Tandai Belum Dibaca
                            </button>
                        @endif
                        <button wire:click="delete" wire:confirm="Apakah Anda yakin ingin menghapus pesan ini?"
                            class="btn btn-sm btn-error">
                            <x-mary-icon name="o-trash" class="w-4 h-4 mr-2" />
                            Hapus
                        </button>
                    </div>
                </div>
            </x-mary-card>
        </div>

        <!-- Isi Pesan -->
        <div class="md:col-span-2">
            <x-mary-card>
                <div class="space-y-4">
                    <!-- Subjek -->
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ $message->subjek }}</h2>
                    </div>

                    <!-- Pesan -->
                    <div class="border-t pt-4">
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="whitespace-pre-line">{{ $message->pesan }}</p>
                        </div>
                    </div>
                </div>
            </x-mary-card>
        </div>
    </div>
</div>
