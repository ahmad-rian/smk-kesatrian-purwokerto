<!-- News Analytics Dashboard -->
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-base-content">Analytics Berita</h2>
            <p class="text-base-content/60">Statistik pengunjung dan performa berita</p>
        </div>

        <!-- Period Selector -->
        <div class="flex items-center gap-2">
            <label class="text-sm font-medium text-base-content/70">Periode:</label>
            <select wire:model.live="selectedPeriod" class="select select-sm select-bordered">
                <option value="7">7 Hari Terakhir</option>
                <option value="14">14 Hari Terakhir</option>
                <option value="30">30 Hari Terakhir</option>
                <option value="90">90 Hari Terakhir</option>
            </select>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-primary/10 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary/70 text-sm font-medium">Total Berita</p>
                    <p class="text-2xl font-bold text-primary">{{ number_format($totalStats['total_news'] ?? 0) }}</p>
                </div>
                <div class="p-3 bg-primary/20 rounded-lg">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-success/10 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-success/70 text-sm font-medium">Total Pengunjung</p>
                    <p class="text-2xl font-bold text-success">{{ number_format($totalStats['total_visitors'] ?? 0) }}
                    </p>
                </div>
                <div class="p-3 bg-success/20 rounded-lg">
                    <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-info/10 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-info/70 text-sm font-medium">Hari Ini</p>
                    <p class="text-2xl font-bold text-info">{{ number_format($totalStats['today_visitors'] ?? 0) }}</p>
                </div>
                <div class="p-3 bg-info/20 rounded-lg">
                    <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-warning/10 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-warning/70 text-sm font-medium">Rata-rata Harian</p>
                    <p class="text-2xl font-bold text-warning">{{ number_format($totalStats['avg_daily'] ?? 0) }}</p>
                </div>
                <div class="p-3 bg-warning/20 rounded-lg">
                    <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Visitor Chart -->
        <div class="bg-base-100 rounded-xl border border-base-300 p-6">
            <h3 class="text-lg font-semibold text-base-content mb-4">Pengunjung Harian</h3>

            @if (count($chartData) > 0)
                <div class="space-y-3">
                    @foreach ($chartData as $data)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-base-content/70 w-16">{{ $data['date'] }}</span>
                            <div class="flex-1 mx-3">
                                <div class="w-full bg-base-300 rounded-full h-2">
                                    @php
                                        $maxVisitors = collect($chartData)->max('visitors') ?: 1;
                                        $percentage = ($data['visitors'] / $maxVisitors) * 100;
                                    @endphp
                                    <div class="bg-primary h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            <span
                                class="text-sm font-medium text-base-content w-12 text-right">{{ $data['visitors'] }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-base-content/50">
                    <p>Belum ada data pengunjung</p>
                </div>
            @endif
        </div>

        <!-- Top News -->
        <div class="bg-base-100 rounded-xl border border-base-300 p-6">
            <h3 class="text-lg font-semibold text-base-content mb-4">Berita Terpopuler</h3>

            @if (count($topNews) > 0)
                <div class="space-y-4">
                    @foreach ($topNews as $index => $item)
                        <div class="flex items-start gap-3">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-primary/20 text-primary rounded-full flex items-center justify-center text-sm font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-medium text-base-content line-clamp-2">
                                    {{ $item['news']['judul'] ?? 'Unknown' }}
                                </h4>
                                <p class="text-xs text-base-content/60 mt-1">
                                    {{ number_format($item['visitors']) }} pengunjung
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-base-content/50">
                    <p>Belum ada data berita populer</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Refresh Button -->
    <div class="text-center">
        <button wire:click="loadAnalytics" class="btn btn-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            Refresh Data
        </button>
    </div>
</div>
