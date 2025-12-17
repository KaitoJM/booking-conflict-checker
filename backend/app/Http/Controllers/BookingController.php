<?php

namespace App\Http\Controllers;

use App\Http\Requests\booking\ConflictReportRequest;
use App\Http\Requests\booking\CreateBookingRequest;
use App\Http\Requests\booking\GetBookingRequest;
use App\Http\Requests\booking\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\UserResource;
use App\Services\BookingConflictService;
use App\Services\BookingService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BookingController extends Controller
{
    protected BookingService $booking_service;
    protected BookingConflictService $booking_conflict_service;

    public function __construct(
        BookingService $booking_service,
        BookingConflictService $booking_conflict_service
    )
    {
        $this->booking_service = $booking_service;
        $this->booking_conflict_service = $booking_conflict_service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetBookingRequest $request)
    {
        $user = Auth::user();

        $bookings = $this->booking_service->getBookings(
            $request->query('search'),
            $user->role == 'admin' ? null : $user->id
        );

        return BookingResource::collection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookingRequest $request)
    {
        $user = Auth::user();

        $booking = $this->booking_service->createBooking(
            $user->id,
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
            Gate::authorize('view', $booking);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json(new BookingResource($booking));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, string $id)
    {
        $booking = $this->booking_service->getBooking($id);
        Gate::authorize('update', $booking);

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
        $booking = $this->booking_service->getBooking($id);
        Gate::authorize('delete', $booking);

        try {
            $this->booking_service->deleteBooking($id);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        return response()->json(null, 204);
    }

    public function conflictReport(ConflictReportRequest $request)
    {
        $bookings = $this->booking_service->getBookings($request->date);

        $report = $this->booking_conflict_service->analyze(
            $bookings,
            $request->date,
            $request->start_time,
            $request->end_time
        );

        return response()->json($report);
    }
}
