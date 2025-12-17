<?php

namespace App\Http\Controllers;

use App\Http\Requests\booking\CreateBookingRequest;
use App\Http\Requests\booking\GetBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\UserResource;
use App\Services\BookingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingService $booking_service;

    public function __construct(BookingService $booking_service)
    {
        $this->booking_service = $booking_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetBookingRequest $request)
    {
        $bookings = $this->booking_service->getBookings(
            $request->query('search'),
            $request->query('user_id')
        );

        return BookingResource::collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookingRequest $request)
    {
        $booking = $this->booking_service->createBooking(
            $request->user_id,
            $request->date,
            $request->start_time,
            $request->end_time
        );

        return response()
            ->json(new BookingResource($booking))
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $booking = $this->booking_service->getBooking($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json(new UserResource($booking));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $booking = $this->booking_service->updateBooking(
                $id,
                $request->validated()
            );
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()
            ->json(new BookingResource($booking));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->booking_service->deleteBooking($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json(null, 204);
    }
}
