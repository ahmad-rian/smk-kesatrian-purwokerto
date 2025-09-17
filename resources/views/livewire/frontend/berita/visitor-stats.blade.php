<!-- Visitor Statistics Component -->
<div class="bg-base-100 rounded-3xl shadow-lg border border-base-300 overflow-hidden">
    <div class="p-6">
        <h3 class="text-xl font-bold text-base-content mb-6 flex items-center"
            style="font-family: 'Bricolage Grotesque', sans-serif;">
            <div class="p-2 bg-info/20 rounded-xl mr-3">
                <svg class="w-6 h-6 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
            </div>
            Statistik Kunjungan
        </h3>

        <div class="grid grid-cols-2 gap-4">
            <!-- Total Visitors -->
            <div class="bg-primary/10 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-primary mb-1">
                    {{ number_format($stats['total'] ?? 0) }}
                </div>
                <div class="text-xs text-base-content/60 font-medium">Total</div>
            </div>

            <!-- Today -->
            <div class="bg-success/10 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-success mb-1">
                    {{ number_format($stats['today'] ?? 0) }}
                </div>
                <div class="text-xs text-base-content/60 font-medium">Hari Ini</div>
            </div>

            <!-- Yesterday -->
            <div class="bg-warning/10 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-warning mb-1">
                    {{ number_format($stats['yesterday'] ?? 0) }}
                </div>
                <div class="text-xs text-base-content/60 font-medium">Kemarin</div>
            </div>

            <!-- This Week -->
            <div class="bg-info/10 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-info mb-1">
                    {{ number_format($stats['this_week'] ?? 0) }}
                </div>
                <div class="text-xs text-base-content/60 font-medium">Minggu Ini</div>
            </div>
        </div>

        <!-- Refresh Button -->
        <div class="mt-4 text-center">
            <button wire:click="loadStats" class="btn btn-sm btn-ghost text-xs">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                    </path>
                </svg>
                Refresh Data
            </button>
        </div>
    </div>
</div>
