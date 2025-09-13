<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-base-content">Pesan Kontak</h1>
            <p class="text-base-content/70 mt-1">Kelola pesan yang diterima dari form kontak website</p>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session('message'))
        <x-mary-alert class="alert-success mb-6" icon="o-check-circle">
            {{ session('message') }}
        </x-mary-alert>
    @endif

    <!-- Search & Filter Section -->
    <div class="mb-6">
        <x-mary-card>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <x-mary-input wire:model.live.debounce.300ms="search"
                        placeholder="Cari berdasarkan nama, email, atau subjek..." icon="o-magnifying-glass" clearable />
                </div>

                <div class="w-full md:w-48">
                    <x-mary-select wire:model.live="status" :options="[
                        ['value' => '', 'label' => 'Semua Status'],
                        ['value' => 'unread', 'label' => 'Belum Dibaca'],
                        ['value' => 'read', 'label' => 'Sudah Dibaca'],
                    ]" option-value="value" option-label="label"
                        placeholder="Filter Status" />
                </div>
            </div>
        </x-mary-card>
    </div>

    <!-- Messages Table -->
    <x-mary-card>
        @if ($messages->count() > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th class="w-24">Status</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th class="w-32">Tanggal</th>
                            <th class="w-40">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr @class(['font-semibold' => $message->isUnread()])>
                                <td>
                                    @if ($message->isUnread())
                                        <x-mary-badge value="Baru" class="badge-primary badge-sm">
                                            <x-slot:prepend>
                                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                            </x-slot:prepend>
                                        </x-mary-badge>
                                    @else
                                        <x-mary-badge value="Dibaca" class="badge-ghost badge-sm">
                                            <x-slot:prepend>
                                                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                                            </x-slot:prepend>
                                        </x-mary-badge>
                                    @endif
                                </td>
                                <td>
                                    <div class="font-medium text-base-content">{{ $message->nama }}</div>
                                </td>
                                <td>
                                    <div class="text-sm text-base-content/70">{{ $message->email }}</div>
                                </td>
                                <td>
                                    <div class="text-sm">{{ Str::limit($message->subjek, 40) }}</div>
                                </td>
                                <td>
                                    <div class="text-sm">
                                        <div>{{ $message->created_at->format('d M Y') }}</div>
                                        <div class="text-xs text-base-content/60">
                                            {{ $message->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex gap-1">
                                        <x-mary-button icon="o-eye"
                                            link="{{ route('admin.contact-messages.show', $message->id) }}"
                                            class="btn-sm btn-outline" tooltip="Lihat pesan" />

                                        @if ($message->isRead())
                                            <x-mary-button icon="o-envelope"
                                                wire:click="markAsUnread('{{ $message->id }}')"
                                                class="btn-sm btn-outline" tooltip="Tandai belum dibaca" />
                                        @else
                                            <x-mary-button icon="o-envelope-open"
                                                wire:click="markAsRead('{{ $message->id }}')"
                                                class="btn-sm btn-outline" tooltip="Tandai sudah dibaca" />
                                        @endif

                                        <x-mary-button icon="o-trash" wire:click="delete('{{ $message->id }}')"
                                            wire:confirm="Apakah Anda yakin ingin menghapus pesan ini?"
                                            class="btn-sm btn-outline btn-error" tooltip="Hapus pesan" />
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($messages->hasPages())
                <div class="mt-6">
                    {{ $messages->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <x-mary-icon name="o-envelope" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-base-content mb-2">
                    @if ($search || $status)
                        Tidak ada pesan yang sesuai
                    @else
                        Belum ada pesan kontak
                    @endif
                </h3>
                <p class="text-base-content/70 mb-6">
                    @if ($search || $status)
                        Tidak ada pesan yang sesuai dengan filter pencarian Anda.
                    @else
                        Pesan yang dikirim melalui form kontak akan muncul di sini.
                    @endif
                </p>
                @if ($search || $status)
                    <x-mary-button label="Reset Filter" icon="o-arrow-path" wire:click="resetFilters"
                        class="btn-outline" />
                @endif
            </div>
        @endif
    </x-mary-card>
</div>
