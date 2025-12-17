<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class BookingService {

    public function getBookings(?string $date = null, ?string $user_id= null) {
        $users = Booking::when($date, function($query) use ($date) {
            $query->where('date', $date);
        })->when($user_id, function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        });

        return $users->get();
    }

    public function createBooking($user_id, $date, $start_time, $end_time) {
        $created = Booking::create([
            'user_id' => $user_id,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
        ]);

        return $created;
    }

    public function getBooking($id) {
        return Booking::findorFail($id);
    }

    public function updateBooking($id, $params) {
        $booking = $this->getBooking($id);

        $booking->update($params);

        return $booking;
    }

    public function deleteBooking($id) {
        $booking = $this->getBooking($id);

        $booking->delete();

        return $booking;
    }
}
