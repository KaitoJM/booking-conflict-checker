<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

class BookingService {

    public function getBookings(?string $search = null, ?string $date = null, ?string $user_id= null) {
        $users = Booking::when($date, function($query) use ($date) {
            $query->where('date', $date);
        })->when($user_id, function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->when($search, function($query) use ($search) {
            $query->where('description', 'like', "%{$search}%");
        })
        ->with('user')
        ->orderBy('date', 'asc')
        ->orderBy('start_time', 'asc');;

        return $users->get();
    }

    public function createBooking($user_id, $description, $date, $start_time, $end_time) {
        $created = Booking::create([
            'user_id' => $user_id,
            'description' => $description,
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
