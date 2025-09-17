<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NewsVisitorService;

class CleanupVisitorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'visitor:cleanup {--days=30 : Number of days to keep detailed visitor IPs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old visitor IP data while keeping visitor counts for privacy and storage optimization';

    /**
     * Execute the console command.
     */
    public function handle(NewsVisitorService $visitorService): int
    {
        $days = $this->option('days');

        $this->info("Cleaning up visitor IP data older than {$days} days...");

        $visitorService->cleanupOldData($days);

        $this->info('Visitor data cleanup completed successfully!');

        return Command::SUCCESS;
    }
}
