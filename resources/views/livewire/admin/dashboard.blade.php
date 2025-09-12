{{-- SPA-Ready Admin Dashboard - Responsive Fixed --}}
<div class="space-y-4 sm:space-y-6 p-4 sm:p-0" id="dashboard-container">

    <!-- Dashboard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1">
            <h1 class="text-2xl sm:text-3xl font-bold text-base-content">{{ __('Dashboard') }}</h1>
            <p class="text-sm sm:text-base text-base-content/70 mt-1">
                {{ __('Selamat datang di SMK Kesatrian Dashboard') }}</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <!-- Refresh Button -->
            <x-mary-button icon="o-arrow-path" class="btn-ghost btn-sm sm:btn-md w-full sm:w-auto" wire:click="refreshData"
                id="refresh-btn">
                <span class="hidden sm:inline">{{ __('Refresh') }}</span>
                <span class="sm:hidden">{{ __('Perbarui') }}</span>
            </x-mary-button>

            <!-- Add Data Button -->
            <x-mary-button icon="o-plus" class="btn-primary btn-sm sm:btn-md w-full sm:w-auto"
                wire:click="goToAddStudent">
                {{ __('Tambah Data') }}
            </x-mary-button>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('status'))
        <x-mary-alert icon="o-check-circle" class="alert-success mb-4">
            {{ session('status') }}
        </x-mary-alert>
    @endif

    <!-- Stats Cards with Real Data - Improved Mobile Layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6">
        <!-- Total Siswa -->
        <x-mary-card class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
            <div class="flex items-center justify-between p-4 sm:p-6">
                <div class="flex-1 min-w-0">
                    <p class="text-blue-100 text-xs sm:text-sm font-medium truncate">{{ __('Total Siswa') }}</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ number_format($totalSiswa) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-blue-400/30 rounded-full flex-shrink-0 ml-2">
                    <x-mary-icon name="o-academic-cap" class="w-5 h-5 sm:w-6 sm:h-6" />
                </div>
            </div>
        </x-mary-card>

        <!-- Total Guru -->
        <x-mary-card class="bg-gradient-to-r from-green-500 to-green-600 text-white">
            <div class="flex items-center justify-between p-4 sm:p-6">
                <div class="flex-1 min-w-0">
                    <p class="text-green-100 text-xs sm:text-sm font-medium truncate">{{ __('Total Guru') }}</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ number_format($totalGuru) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-green-400/30 rounded-full flex-shrink-0 ml-2">
                    <x-mary-icon name="o-user-group" class="w-5 h-5 sm:w-6 sm:h-6" />
                </div>
            </div>
        </x-mary-card>

        <!-- Total Kelas -->
        <x-mary-card class="bg-gradient-to-r from-purple-500 to-purple-600 text-white">
            <div class="flex items-center justify-between p-4 sm:p-6">
                <div class="flex-1 min-w-0">
                    <p class="text-purple-100 text-xs sm:text-sm font-medium truncate">{{ __('Total Kelas') }}</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ number_format($totalKelas) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-purple-400/30 rounded-full flex-shrink-0 ml-2">
                    <x-mary-icon name="o-building-office" class="w-5 h-5 sm:w-6 sm:h-6" />
                </div>
            </div>
        </x-mary-card>

        <!-- Total Mata Pelajaran -->
        <x-mary-card class="bg-gradient-to-r from-orange-500 to-orange-600 text-white">
            <div class="flex items-center justify-between p-4 sm:p-6">
                <div class="flex-1 min-w-0">
                    <p class="text-orange-100 text-xs sm:text-sm font-medium truncate">{{ __('Mata Pelajaran') }}</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ number_format($totalMapel) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-orange-400/30 rounded-full flex-shrink-0 ml-2">
                    <x-mary-icon name="o-book-open" class="w-5 h-5 sm:w-6 sm:h-6" />
                </div>
            </div>
        </x-mary-card>
    </div>

    <!-- Recent Activities - Mobile First Design -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
        <!-- Recent Students -->
        <x-mary-card class="order-2 xl:order-1">
            <x-slot:title
                class="text-lg sm:text-xl font-semibold p-4 sm:p-6 pb-2 sm:pb-3">{{ __('Siswa Terbaru') }}</x-slot:title>

            <div class="space-y-2 sm:space-y-3 p-4 sm:p-6 pt-0">
                @for ($i = 1; $i <= 5; $i++)
                    <div
                        class="flex items-center space-x-3 p-2 sm:p-3 bg-base-200 rounded-lg hover:bg-base-300 transition-colors cursor-pointer">
                        <div class="avatar placeholder flex-shrink-0">
                            <div class="bg-neutral text-neutral-content rounded-full w-8 sm:w-10">
                                <span class="text-xs sm:text-sm">S{{ $i }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm sm:text-base truncate">Siswa {{ $i }}</p>
                            <p class="text-xs sm:text-sm text-base-content/70 truncate">Kelas XII RPL
                                {{ $i }}</p>
                        </div>
                        <div class="text-xs sm:text-sm text-base-content/70 flex-shrink-0">
                            <span class="hidden sm:inline">{{ now()->subDays($i)->format('d M Y') }}</span>
                            <span class="sm:hidden">{{ now()->subDays($i)->format('d/m') }}</span>
                        </div>
                    </div>
                @endfor
            </div>
        </x-mary-card>

        <!-- Quick Actions with Better Mobile Layout -->
        <x-mary-card class="order-1 xl:order-2">
            <x-slot:title
                class="text-lg sm:text-xl font-semibold p-4 sm:p-6 pb-2 sm:pb-3">{{ __('Aksi Cepat') }}</x-slot:title>

            <div class="grid grid-cols-2 gap-3 sm:gap-4 p-4 sm:p-6 pt-0">
                <x-mary-button
                    class="btn-outline btn-primary h-16 sm:h-20 flex-col hover:scale-105 transition-transform text-center"
                    wire:click="goToAddStudent">
                    <x-mary-icon name="o-user-plus" class="w-5 h-5 sm:w-6 sm:h-6 mb-1 sm:mb-2" />
                    <span class="text-xs sm:text-sm leading-tight">{{ __('Tambah Siswa') }}</span>
                </x-mary-button>

                <x-mary-button
                    class="btn-outline btn-secondary h-16 sm:h-20 flex-col hover:scale-105 transition-transform text-center"
                    wire:click="goToCreateClass">
                    <x-mary-icon name="o-document-plus" class="w-5 h-5 sm:w-6 sm:h-6 mb-1 sm:mb-2" />
                    <span class="text-xs sm:text-sm leading-tight">{{ __('Buat Kelas') }}</span>
                </x-mary-button>

                <x-mary-button
                    class="btn-outline btn-accent h-16 sm:h-20 flex-col hover:scale-105 transition-transform text-center"
                    wire:click="goToReports">
                    <x-mary-icon name="o-chart-bar" class="w-5 h-5 sm:w-6 sm:h-6 mb-1 sm:mb-2" />
                    <span class="text-xs sm:text-sm leading-tight">{{ __('Lihat Laporan') }}</span>
                </x-mary-button>

                <x-mary-button
                    class="btn-outline btn-info h-16 sm:h-20 flex-col hover:scale-105 transition-transform text-center"
                    wire:click="goToSettings">
                    <x-mary-icon name="o-cog-6-tooth" class="w-5 h-5 sm:w-6 sm:h-6 mb-1 sm:mb-2" />
                    <span class="text-xs sm:text-sm leading-tight">{{ __('Pengaturan') }}</span>
                </x-mary-button>
            </div>
        </x-mary-card>
    </div>

    <!-- Loading State for Actions - Mobile Optimized -->
    <div wire:loading.flex wire:target="refreshData,goToAddStudent,goToCreateClass,goToReports,goToSettings"
        class="fixed inset-0 bg-black/20 backdrop-blur-sm z-50 items-center justify-center p-4">
        <div class="bg-base-100 rounded-lg p-4 sm:p-6 shadow-xl max-w-sm w-full mx-auto">
            <div class="flex items-center gap-3 sm:gap-4">
                <span class="loading loading-spinner loading-md"></span>
                <span class="text-sm sm:text-base text-base-content">{{ __('Memproses...') }}</span>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        const refreshBtn = document.getElementById('refresh-btn');

        // Listen for dashboard refresh events
        Livewire.on('dashboard-refreshed', () => {
            // Remove loading state from refresh button
            if (refreshBtn) {
                refreshBtn.classList.remove('loading');
            }

            // Show success toast (if you have a toast system)
            console.log('Dashboard berhasil diperbarui!');
        });

        // Add loading state when refresh button is clicked
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                refreshBtn.classList.add('loading');
            });
        }
    });
</script>
