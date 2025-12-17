<?php

namespace App\Services;

class BookingCleanupService
{
    public function __construct(
        private BookingService $booking_service
    ) {}

    public function deleteBookingsOlderThanDays(int $days): int
    {
        $cutoffDate = now()->subDays($days);

        return $this->booking_service->deleteOlderThan($cutoffDate);
    }
}
