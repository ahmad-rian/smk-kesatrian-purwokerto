{{-- SPA-Ready Admin Dashboard - Mary UI Compatible --}}
<div class="space-y-4 sm:space-y-6 p-4 sm:p-0" id="dashboard-container">

    <!-- Dashboard Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl sm:text-3xl font-bold text-base-content">{{ __('Dashboard') }}</h1>
                @if ($autoRefresh)
                    <div class="badge badge-success badge-sm animate-pulse">
                        <div class="w-2 h-2 bg-white rounded-full mr-1 animate-ping"></div>
                        Live
                    </div>
                @endif
            </div>
            <p class="text-sm sm:text-base text-base-content/70">
                {{ __('Selamat datang di SMK Kesatrian Dashboard') }}</p>
            @if ($autoRefresh)
                <p class="text-xs text-success mt-1">{{ __('Data diperbarui otomatis setiap 30 detik') }}</p>
            @endif
        </div>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <!-- Auto Refresh Toggle -->
            {{-- <x-mary-button wire:click="toggleAutoRefresh" :icon="$autoRefresh ? 'o-pause' : 'o-play'" class="btn-sm sm:btn-md w-full sm:w-auto"
                :class="$autoRefresh ? 'btn-success' : 'btn-outline'">
                <span class="hidden sm:inline ml-1">{{ $autoRefresh ? __('Stop Auto') : __('Auto Refresh') }}</span>
                <span class="sm:hidden">{{ $autoRefresh ? __('Stop') : __('Auto') }}</span>
            </x-mary-button> --}}

            <!-- Manual Refresh Button -->
            <x-mary-button wire:click="refreshData" icon="o-arrow-path"
                class="btn-primary btn-sm sm:btn-md w-full sm:w-auto" spinner="refreshData" id="refresh-btn">
                <span class="hidden sm:inline ml-1">{{ __('Refresh') }}</span>
                <span class="sm:hidden">{{ __('Perbarui') }}</span>
            </x-mary-button>

        </div>
    </div>

    <!-- Success Message -->
    @if (session('status'))
        <x-mary-alert icon="o-check-circle" class="alert-success mb-4">
            {{ session('status') }}
        </x-mary-alert>
    @endif

    <!-- Stats Cards with Real Data -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-3 sm:gap-2 lg:gap-2">



        <!-- Pengunjung Hari Ini -->
        <x-mary-card class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white">
            <div class="flex items-center justify-between p-4 sm:p-6">
                <div class="flex-1 min-w-0">
                    <p class="text-cyan-100 text-xs sm:text-sm font-medium truncate">{{ __('Pengunjung Hari Ini') }}
                    </p>
                    <p class="text-xl sm:text-2xl font-bold">{{ number_format($todayVisitors) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-cyan-400/30 rounded-full flex-shrink-0 ml-2">
                    <x-mary-icon name="o-eye" class="w-5 h-5 sm:w-6 sm:h-6" />
                </div>
            </div>
        </x-mary-card>

        <!-- Total Pengunjung -->
        <x-mary-card class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white">
            <div class="flex items-center justify-between p-4 sm:p-6">
                <div class="flex-1 min-w-0">
                    <p class="text-indigo-100 text-xs sm:text-sm font-medium truncate">{{ __('Total Pengunjung') }}</p>
                    <p class="text-xl sm:text-2xl font-bold">{{ number_format($totalVisitors) }}</p>
                </div>
                <div class="p-2 sm:p-3 bg-indigo-400/30 rounded-full flex-shrink-0 ml-2">
                    <x-mary-icon name="o-users" class="w-5 h-5 sm:w-6 sm:h-6" />
                </div>
            </div>
        </x-mary-card>
    </div>

    <!-- Charts Section - Using Mary UI Chart Component -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <!-- Facilities Distribution Chart -->
        <x-mary-card>
            <x-slot:title class="text-lg sm:text-xl font-semibold">
                <div class="flex items-center justify-between">
                    <span>{{ __('Distribusi Fasilitas') }}</span>
                    <div class="badge badge-primary badge-outline">
                        <x-mary-icon name="o-chart-pie" class="w-4 h-4 mr-1" />
                        Chart
                    </div>
                </div>
            </x-slot:title>

            <div class="h-64 sm:h-80 transition-all duration-500 hover:scale-[1.02]">
                @if (!empty($facilitiesChart['data']['labels']))
                    <x-mary-chart wire:model="facilitiesChart" />
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Tidak ada data fasilitas
                    </div>
                @endif
            </div>
        </x-mary-card>

        <!-- Activities Trend Chart -->
        <x-mary-card>
            <x-slot:title class="text-lg sm:text-xl font-semibold">
                <div class="flex items-center justify-between">
                    <span>{{ __('Trend Kegiatan Sekolah') }}</span>
                    <div class="badge badge-secondary badge-outline">
                        <x-mary-icon name="o-chart-bar" class="w-4 h-4 mr-1" />
                        Trend
                    </div>
                </div>
            </x-slot:title>

            <div class="h-64 sm:h-80 transition-all duration-500 hover:scale-[1.02]">
                @if (!empty($activitiesChart['data']['labels']))
                    <x-mary-chart wire:model="activitiesChart" />
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Tidak ada data kegiatan
                    </div>
                @endif
            </div>
        </x-mary-card>
    </div>

    <!-- Additional Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-6">
        <!-- Website Visitor Trend Chart -->
        <x-mary-card>
            <x-slot:title class="text-lg sm:text-xl font-semibold">
                <div class="flex items-center justify-between">
                    <span>{{ __('Trend Pengunjung Website') }}</span>
                    <div class="badge badge-info badge-outline">
                        <x-mary-icon name="o-eye" class="w-4 h-4 mr-1" />
                        7 Hari
                    </div>
                </div>
            </x-slot:title>

            <div class="h-64 sm:h-80 transition-all duration-500 hover:scale-[1.02]">
                @if (!empty($visitorChart['data']['labels']))
                    <x-mary-chart wire:model="visitorChart" />
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        <div class="text-center">
                            <x-mary-icon name="o-chart-bar" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                            <p class="text-sm">Belum ada data pengunjung</p>
                        </div>
                    </div>
                @endif
            </div>
        </x-mary-card>

        <!-- Study Programs Chart -->
        <x-mary-card>
            <x-slot:title class="text-lg sm:text-xl font-semibold">
                <div class="flex items-center justify-between">
                    <span>{{ __('Fasilitas per Program Studi') }}</span>
                    <div class="badge badge-accent badge-outline">
                        <x-mary-icon name="o-academic-cap" class="w-4 h-4 mr-1" />
                        Program
                    </div>
                </div>
            </x-slot:title>

            <div class="h-64 sm:h-80 transition-all duration-500 hover:scale-[1.02]">
                @if (!empty($studyProgramsChart['data']['labels']))
                    <x-mary-chart wire:model="studyProgramsChart" />
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Tidak ada data program studi
                    </div>
                @endif
            </div>
        </x-mary-card>

        <!-- Monthly Data Chart -->
        <x-mary-card>
            <x-slot:title class="text-lg sm:text-xl font-semibold">
                <div class="flex items-center justify-between">
                    <span>{{ __('Konten Baru Bulanan') }}</span>
                    <div class="badge badge-info badge-outline">
                        <x-mary-icon name="o-calendar-days" class="w-4 h-4 mr-1" />
                        Monthly
                    </div>
                </div>
            </x-slot:title>

            <div class="h-64 sm:h-80 transition-all duration-500 hover:scale-[1.02]">
                @if (!empty($monthlyChart['data']['labels']))
                    <x-mary-chart wire:model="monthlyChart" />
                @else
                    <div class="flex items-center justify-center h-full text-gray-500">
                        Tidak ada data bulanan
                    </div>
                @endif
            </div>
        </x-mary-card>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
        <!-- Recent Students -->
        <x-mary-card>
            <x-slot:title class="text-lg sm:text-xl font-semibold">{{ __('Siswa Terbaru') }}</x-slot:title>

            <div class="space-y-2 sm:space-y-3">
                @forelse ($recentStudents as $student)
                    <div
                        class="flex items-center space-x-3 p-2 sm:p-3 bg-base-200 rounded-lg hover:bg-base-300 transition-colors cursor-pointer">
                        <div class="avatar flex-shrink-0">
                            <div class="w-8 sm:w-10 rounded-full">
                                <img src="{{ $student['avatar'] }}" alt="{{ $student['name'] }}"
                                    class="rounded-full" />
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-sm sm:text-base truncate">{{ $student['name'] }}</p>
                            <p class="text-xs sm:text-sm text-base-content/70 truncate">{{ $student['class'] }}</p>
                        </div>
                        <div class="text-xs sm:text-sm text-base-content/70 flex-shrink-0">
                            <span class="truncate">{{ $student['joined'] }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-base-content/70">
                        <x-mary-icon name="o-users" class="w-12 h-12 mx-auto mb-2 opacity-50" />
                        <p class="text-sm">{{ __('Belum ada siswa terdaftar') }}</p>
                    </div>
                @endforelse
            </div>
        </x-mary-card>

        {{-- <!-- Quick Actions -->
        <x-mary-card>
            <x-slot:title class="text-lg sm:text-xl font-semibold">{{ __('Aksi Cepat') }}</x-slot:title>

            <div class="grid grid-cols-2 gap-3 sm:gap-4">
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
        </x-mary-card> --}}
    </div>

    <!-- Loading State -->
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
    // Dashboard Auto-refresh functionality
    document.addEventListener('DOMContentLoaded', function() {
        let autoRefreshInterval;
        const refreshBtn = document.getElementById('refresh-btn');

        // Handle manual refresh button loading state
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                this.classList.add('loading');
            });
        }

        // Remove loading state when refresh is complete
        window.addEventListener('dashboard-refreshed', function() {
            if (refreshBtn) {
                refreshBtn.classList.remove('loading');
            }
        });

        // Auto refresh functionality
        window.addEventListener('start-auto-refresh', function() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }

            autoRefreshInterval = setInterval(function() {
                if (typeof Livewire !== 'undefined') {
                    Livewire.dispatch('auto-refresh-dashboard');
                }
            }, 30000);

            console.log('Auto refresh started - updating every 30 seconds');
        });

        window.addEventListener('stop-auto-refresh', function() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
                console.log('Auto refresh stopped');
            }
        });

        window.addEventListener('charts-updated', function() {
            console.log('Charts updated with new data');
            // Trigger chart re-render if needed
            if (typeof Chart !== 'undefined') {
                Chart.helpers.each(Chart.instances, function(instance) {
                    instance.update();
                });
            }
        });

        window.addEventListener('beforeunload', function() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
            }
        });

        // Initialize charts on page load
        window.addEventListener('livewire:navigated', function() {
            console.log('Dashboard loaded - charts should be initialized');
        });
    });
</script>
