<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Carbon\Carbon;

class BookingConflictService
{
    public function analyze(
        Collection $bookings,
        string $date,
        string $startTime,
        string $endTime
    ): array {
        $start = Carbon::createFromFormat('H:i:s', $startTime);
        $end   = Carbon::createFromFormat('H:i:s', $endTime);

        $overlaps = [];
        $conflicts = [];
        $gaps = [];

        $sorted = $bookings
            ->where('date', $date)
            ->sortBy('start_time')
            ->values();

        foreach ($sorted as $booking) {
            $bookingStart = Carbon::createFromFormat('H:i:s', $booking->start_time);
            $bookingEnd   = Carbon::createFromFormat('H:i:s', $booking->end_time);

            // Exact conflict (same start & end)
            if (
                $bookingStart->eq($start) &&
                $bookingEnd->eq($end)
            ) {
                $conflicts[] = $booking;
            }

            // Overlapping
            if (
                $start < $bookingEnd &&
                $end > $bookingStart
            ) {
                $overlaps[] = $booking;
            }
        }

        // Gaps detection
        for ($i = 0; $i < $sorted->count() - 1; $i++) {
            $currentEnd = Carbon::createFromFormat('H:i:s', $sorted[$i]->end_time);
            $nextStart  = Carbon::createFromFormat('H:i:s', $sorted[$i + 1]->start_time);

            if ($currentEnd->lt($nextStart)) {
                $gaps[] = [
                    'start_time' => $currentEnd->format('H:i:s'),
                    'end_time'   => $nextStart->format('H:i:s'),
                    'duration'  => $currentEnd->diffInMinutes($nextStart) . ' minutes',
                ];
            }
        }

        return [
            'input' => [
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ],
            'summary' => [
                'has_conflicts'  => count($conflicts) > 0,
                'has_overlaps'   => count($overlaps) > 0,
                'total_gaps'     => count($gaps),
            ],
            'conflicts' => $conflicts,
            'overlaps'  => $overlaps,
            'gaps'      => $gaps,
        ];
    }
}
