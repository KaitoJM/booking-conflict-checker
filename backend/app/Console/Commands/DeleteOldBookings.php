<?php

namespace App\Console\Commands;

use App\Services\BookingCleanupService;
use Illuminate\Console\Command;

class DeleteOldBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete bookings older than 30 days';

    public function __construct(
        private BookingCleanupService $cleanup_service
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = $this->cleanup_service->deleteBookingsOlderThanDays(30);

        $this->info("Deleted {$count} old bookings.");

        return Command::SUCCESS;
    }
}
