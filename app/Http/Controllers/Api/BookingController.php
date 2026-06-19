<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\StoreBookingRequest;
use App\Http\Requests\Booking\UpdateBookingRequest;
use App\Models\Booking;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;


class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(Request $request): JsonResponse
    {
        $bookings = $this->bookingService->getAll($request->user());

        return response()->json(
            $bookings->through(fn($booking) => new BookingResource($booking))
        );
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookingService->create(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'data' => new BookingResource($booking)
        ], 201);
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        Gate::authorize('view', $booking);

        $booking->load(['passengers', 'payment']);

        return response()->json([
            'data' => new BookingResource($booking)
        ]);
    }

    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        Gate::authorize('update', $booking);

        $booking = $this->bookingService->update($booking, $request->validated());

        return response()->json([
            'data' => new BookingResource($booking)
        ]);
    }

    public function cancel(Request $request, Booking $booking): JsonResponse
    {
        Gate::authorize('cancel', $booking);

        $booking = $this->bookingService->cancel($booking);

        return response()->json([
            'data' => new BookingResource($booking)
        ]);
    }
}
