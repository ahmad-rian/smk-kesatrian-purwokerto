<?php

namespace App\Console\Commands;

use App\Services\WebsiteVisitorService;
use Illuminate\Console\Command;

class CleanupWebsiteVisitorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'website:cleanup-visitors {--days=30 : Number of days to keep visitor data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup old website visitor data (older than specified days)';

    /**
     * Execute the console command.
     */
    public function handle(WebsiteVisitorService $visitorService)
    {
        $days = $this->option('days');

        $this->info("Cleaning up website visitor data older than {$days} days...");

        $deletedCount = $visitorService->cleanupOldData();

        $this->info("Cleanup completed. Deleted {$deletedCount} old visitor records.");

        return Command::SUCCESS;
    }
}
