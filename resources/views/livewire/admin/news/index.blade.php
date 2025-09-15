<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-base-content mb-2" style="font-family: 'Bricolage Grotesque', sans-serif;">
                Manajemen Berita
            </h1>
            <p class="text-base-content/70" style="font-family: 'Inter', sans-serif;">
                Kelola berita dan informasi terbaru sekolah
            </p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('admin.news.create') }}" class="btn btn-primary" wire:navigate>
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Berita
            </a>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-base-100 rounded-lg shadow-sm border border-base-300 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="label">
                    <span class="label-text font-medium">Cari Berita</span>
                </label>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Cari berdasarkan judul atau konten..." 
                       class="input input-bordered w-full">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="label">
                    <span class="label-text font-medium">Status</span>
                </label>
                <select wire:model.live="statusFilter" class="select select-bordered w-full">
                    <option value="all">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>
            </div>

            <!-- Per Page -->
            <div>
                <label class="label">
                    <span class="label-text font-medium">Tampilkan</span>
                </label>
                <select wire:model.live="perPage" class="select select-bordered w-full">
                    <option value="10">10 per halaman</option>
                    <option value="25">25 per halaman</option>
                    <option value="50">50 per halaman</option>
                </select>
            </div>
        </div>
    </div>

    <!-- News Table -->
    <div class="bg-base-100 rounded-lg shadow-sm border border-base-300 overflow-hidden">
        @if($newsList->count() > 0)
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead class="bg-base-200">
                        <tr>
                            <th class="font-semibold">Gambar</th>
                            <th class="font-semibold">Judul</th>
                            <th class="font-semibold">Status</th>
                            <th class="font-semibold">Tanggal</th>
                            <th class="font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($newsList as $news)
                            <tr class="hover:bg-base-50">
                                <!-- Gambar -->
                                <td>
                                    @if($news->gambar)
                                        <div class="avatar">
                                            <div class="w-16 h-12 rounded">
                                                <img src="{{ Storage::url($news->gambar) }}" 
                                                     alt="{{ $news->judul }}" 
                                                     class="object-cover">
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-16 h-12 bg-base-200 rounded flex items-center justify-center">
                                            <svg class="w-6 h-6 text-base-content/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>

                                <!-- Judul -->
                                <td>
                                    <div class="font-medium text-base-content">{{ $news->judul }}</div>
                                    <div class="text-sm text-base-content/60 mt-1">
                                        {{ Str::limit(strip_tags($news->konten), 80) }}
                                    </div>
                                </td>

                                <!-- Status -->
                                <td>
                                    <button wire:click="toggleStatus({{ $news->id }})" 
                                            class="badge {{ $news->status === 'published' ? 'badge-success' : 'badge-error' }} cursor-pointer hover:opacity-80">
                                        {{ $news->status === 'published' ? 'Aktif' : 'Draft' }}
                                    </button>
                                </td>

                                <!-- Tanggal -->
                                <td>
                                    <div class="text-sm">
                                        {{ $news->created_at->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-base-content/60">
                                        {{ $news->created_at->format('H:i') }}
                                    </div>
                                </td>

                                <!-- Aksi -->
                                <td>
                                    <div class="flex justify-center gap-2">
                                        <!-- Edit -->
                                        <a href="{{ route('admin.news.edit', $news->id) }}" 
                                           class="btn btn-sm btn-ghost btn-square" 
                                           wire:navigate
                                           title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <!-- Delete -->
                                        <button wire:click="confirmDelete({{ $news->id }})" 
                                                class="btn btn-sm btn-ghost btn-square text-error hover:bg-error/10" 
                                                title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="p-4 border-t border-base-300">
                {{ $newsList->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-base-content/40 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-lg font-medium text-base-content mb-2">Belum Ada Berita</h3>
                <p class="text-base-content/60 mb-4">Mulai tambahkan berita pertama untuk sekolah Anda.</p>
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary" wire:navigate>
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Berita Pertama
                </a>
            </div>
        @endif
    </div>
    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal && $newsToDelete)
        <div class="modal modal-open">
            <div class="modal-box">
                <h3 class="font-bold text-lg mb-4">Konfirmasi Hapus</h3>
                <p class="mb-4">Apakah Anda yakin ingin menghapus berita "{{ $newsToDelete->judul }}"?</p>
                <p class="text-warning text-sm mb-6">Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="modal-action">
                    <button wire:click="cancelDelete" class="btn btn-ghost">Batal</button>
                    <button wire:click="deleteNews" class="btn btn-error">Hapus</button>
                </div>
            </div>
        </div>
    @endif
</div>