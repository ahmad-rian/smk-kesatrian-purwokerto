<div>
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="title text-2xl font-bold text-base-content">Kegiatan Sekolah</h1>
            <p class="body text-base-content/70 mt-1">Kelola data kegiatan dan acara sekolah</p>
        </div>
        
        <x-mary-button label="Tambah Kegiatan" icon="o-plus" class="btn-primary"
            link="{{ route('admin.school-activities.create') }}" />
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <x-mary-stat
            title="Total Kegiatan"
            description="Semua kegiatan"
            value="{{ $stats['total'] }}"
            icon="o-calendar-days"
            color="text-blue-500" />
        
        <x-mary-stat
            title="Kegiatan Aktif"
            description="Kegiatan yang aktif"
            value="{{ $stats['active'] }}"
            icon="o-check-circle"
            color="text-green-500" />
        
        <x-mary-stat
            title="Kegiatan Unggulan"
            description="Kegiatan unggulan"
            value="{{ $stats['unggulan'] }}"
            icon="o-star"
            color="text-yellow-500" />
        
        <x-mary-stat
            title="Akan Datang"
            description="Kegiatan mendatang"
            value="{{ $stats['akan_datang'] }}"
            icon="o-clock"
            color="text-purple-500" />
    </div>

    {{-- Filters Section --}}
    <div class="card bg-base-100 shadow-xl mb-6">
        <div class="card-body">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search Input --}}
            <x-mary-input
                wire:model.live.debounce.300ms="search"
                placeholder="Cari kegiatan, kategori, lokasi..."
                icon="o-magnifying-glass"
                clearable />
            
            {{-- Status Filter --}}
            <x-mary-select
                wire:model.live="statusFilter"
                placeholder="Filter Status"
                icon="o-funnel"
                :options="[
                    ['id' => 'all', 'name' => 'Semua Status'],
                    ['id' => 'active', 'name' => 'Aktif'],
                    ['id' => 'inactive', 'name' => 'Nonaktif']
                ]" />
            
            {{-- Unggulan Filter --}}
            <x-mary-select
                wire:model.live="unggulanFilter"
                placeholder="Filter Unggulan"
                icon="o-star"
                :options="[
                    ['id' => 'all', 'name' => 'Semua'],
                    ['id' => 'yes', 'name' => 'Unggulan'],
                    ['id' => 'no', 'name' => 'Biasa']
                ]" />
            
            {{-- Kategori Filter --}}
            <x-mary-select
                wire:model.live="kategoriFilter"
                placeholder="Filter Kategori"
                icon="o-tag"
                :options="array_merge(
                    [['id' => 'all', 'name' => 'Semua Kategori']],
                    collect($kategoriOptions)->map(fn($name, $id) => ['id' => $id, 'name' => $name])->values()->toArray()
                )" />
        </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th class="w-20">Gambar</th>
                        <th>Nama Kegiatan</th>
                        <th class="w-32">Kategori</th>
                        <th class="w-40">Tanggal</th>
                        <th class="w-32">Status</th>
                        <th class="w-24">Unggulan</th>
                        <th class="w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schoolActivities as $activity)
                        <tr>
                            {{-- Gambar Column --}}
                            <td>
                                <div class="flex items-center justify-center">
                                    @if($activity->gambar_utama)
                                        <img src="{{ $activity->gambar_utama_url }}" 
                                             alt="{{ $activity->nama_kegiatan }}"
                                             class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
                                            <x-mary-icon name="o-photo" class="w-6 h-6 text-gray-400" />
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Nama Kegiatan Column --}}
                            <td>
                                <div>
                                    <div class="font-semibold text-gray-900 flex items-center gap-2">
                                        {{ $activity->nama_kegiatan }}
                                        @if($activity->unggulan)
                                            <x-mary-icon name="o-star" class="w-4 h-4 text-yellow-500" />
                                        @endif
                                    </div>
                                    @if($activity->deskripsi)
                                        <div class="text-sm text-gray-500 line-clamp-2">{{ Str::limit($activity->deskripsi, 80) }}</div>
                                    @endif
                                    @if($activity->lokasi)
                                        <div class="flex items-center mt-1">
                                            <x-mary-icon name="o-map-pin" class="w-3 h-3 text-gray-400 mr-1" />
                                            <span class="text-xs text-gray-500">{{ $activity->lokasi }}</span>
                                        </div>
                                    @endif
                                    @if($activity->penanggungjawab)
                                        <div class="flex items-center mt-1">
                                            <x-mary-icon name="o-user" class="w-3 h-3 text-gray-400 mr-1" />
                                            <span class="text-xs text-gray-500">{{ $activity->penanggungjawab }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Kategori Column --}}
                            <td>
                                @if($activity->kategori)
                                    <div class="badge badge-outline badge-sm">
                                        {{ $kategoriOptions[$activity->kategori] ?? $activity->kategori }}
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm italic">-</span>
                                @endif
                            </td>

                            {{-- Tanggal Column --}}
                            <td>
                                <div class="text-sm">
                                    @if($activity->tanggal_mulai)
                                        <div class="flex items-center text-gray-900">
                                            <x-mary-icon name="o-calendar" class="w-3 h-3 mr-1" />
                                            {{ $activity->tanggal_mulai->format('d/m/Y') }}
                                        </div>
                                        @if($activity->tanggal_selesai && $activity->tanggal_selesai != $activity->tanggal_mulai)
                                            <div class="text-gray-500 text-xs mt-1">
                                                s/d {{ $activity->tanggal_selesai->format('d/m/Y') }}
                                            </div>
                                        @endif
                                        
                                        {{-- Status Kegiatan Badge --}}
                                        @php
                                            $statusKegiatan = $activity->status_kegiatan;
                                            $statusClass = match($statusKegiatan) {
                                                'akan_datang' => 'badge-info',
                                                'berlangsung' => 'badge-success',
                                                'selesai' => 'badge-neutral',
                                                default => 'badge-ghost'
                                            };
                                            $statusText = match($statusKegiatan) {
                                                'akan_datang' => 'Akan Datang',
                                                'berlangsung' => 'Berlangsung',
                                                'selesai' => 'Selesai',
                                                default => 'Tidak Terjadwal'
                                            };
                                        @endphp
                                        <div class="badge {{ $statusClass }} badge-xs mt-1">{{ $statusText }}</div>
                                    @else
                                        <span class="text-gray-400 text-sm italic">Belum dijadwalkan</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Status Aktif Column --}}
                            <td>
                                <div class="flex items-center justify-center">
                                    <x-mary-toggle 
                                        wire:click="toggleStatus('{{ $activity->id }}')"
                                        :checked="$activity->aktif"
                                        class="toggle-success" />
                                </div>
                            </td>

                            {{-- Unggulan Column --}}
                            <td>
                                <div class="flex items-center justify-center">
                                    <x-mary-toggle 
                                        wire:click="toggleUnggulan('{{ $activity->id }}')"
                                        :checked="$activity->unggulan"
                                        class="toggle-warning" />
                                </div>
                            </td>

                            {{-- Actions Column --}}
                            <td>
                                <div class="flex items-center gap-2">
                                    <x-mary-button 
                                        icon="o-pencil-square" 
                                        link="{{ route('admin.school-activities.edit', $activity) }}"
                                        class="btn-ghost btn-sm"
                                        tooltip="Edit" />
                                    
                                    <x-mary-button 
                                        icon="o-trash" 
                                        wire:click="confirmDelete('{{ $activity->id }}')"
                                        class="btn-ghost btn-sm text-red-500 hover:text-red-700"
                                        tooltip="Hapus" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12">
                                <x-mary-icon name="o-calendar-days" class="w-16 h-16 text-gray-300 mx-auto mb-4" />
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kegiatan</h3>
                                <p class="text-gray-500 mb-4">Mulai dengan menambahkan kegiatan sekolah pertama.</p>
                                <x-mary-button 
                                    label="Tambah Kegiatan" 
                                    icon="o-plus" 
                                    class="btn-primary"
                                    link="{{ route('admin.school-activities.create') }}" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="mt-4">
            {{ $schoolActivities->links() }}
        </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-mary-modal wire:model="showDeleteModal" title="Konfirmasi Hapus" subtitle="Apakah Anda yakin ingin menghapus kegiatan ini?">
        @if($deleteId)
            @php
                $activityToDelete = $schoolActivities->firstWhere('id', $deleteId);
            @endphp
            @if($activityToDelete)
                <div class="mb-4">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <x-mary-icon name="o-exclamation-triangle" class="w-6 h-6 text-red-500 mr-3" />
                            <div>
                                <h4 class="font-medium text-red-800">{{ $activityToDelete->nama_kegiatan }}</h4>
                                @if($activityToDelete->kategori)
                                    <p class="text-sm text-red-600">Kategori: {{ $kategoriOptions[$activityToDelete->kategori] ?? $activityToDelete->kategori }}</p>
                                @endif
                                @if($activityToDelete->tanggal_mulai)
                                    <p class="text-sm text-red-600">Tanggal: {{ $activityToDelete->tanggal_mulai->format('d/m/Y') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">
                        Data yang dihapus tidak dapat dikembalikan. Pastikan Anda sudah yakin dengan keputusan ini.
                    </p>
                </div>
            @endif
        @endif
        
        <x-slot:actions>
            <x-mary-button label="Batal" wire:click="cancelDelete" class="btn-ghost" />
            <x-mary-button label="Hapus" wire:click="delete" class="btn-error" />
        </x-slot:actions>
    </x-mary-modal>
</div>