<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Passenger\StorePassengerRequest;
use App\Http\Resources\PassengerResource;
use App\Models\Booking;
use App\Models\Passenger;
use App\Services\PassengerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PassengerController extends Controller
{
    public function __construct(private PassengerService $passengerService) {}

    public function index(Booking $booking): JsonResponse
    {
        Gate::authorize('view', $booking);
        $passengers = $booking->passengers()->with('ticket')->get();

        return response()->json([
            'data' => PassengerResource::collection($passengers)
        ]);
    }

    public function store(StorePassengerRequest $request, Booking $booking): JsonResponse
    {
        Gate::authorize('update', $booking);
        $passenger = $this->passengerService->create($booking, $request->validated());

        return response()->json([
            'data' => new PassengerResource($passenger)
        ], 201);
    }

    public function destroy(Booking $booking, Passenger $passenger): JsonResponse
    {
        Gate::authorize('update', $booking);
        $this->passengerService->delete($passenger);


        return response()->json(null, 204);
    }
}
