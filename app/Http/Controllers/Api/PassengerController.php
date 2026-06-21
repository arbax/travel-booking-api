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
use OpenApi\Attributes as OA;

class PassengerController extends Controller
{
    public function __construct(private PassengerService $passengerService) {}

    #[OA\Get(
        path: '/api/bookings/{booking}/passengers',
        summary: 'List passengers for a booking',
        security: [['sanctum' => []]],
        tags: ['Passengers'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'List of passengers'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    
    public function index(Booking $booking): JsonResponse
    {
        Gate::authorize('view', $booking);
        $passengers = $booking->passengers()->with('ticket')->get();

        return response()->json([
            'data' => PassengerResource::collection($passengers)
        ]);
    }

    #[OA\Post(
        path: '/api/bookings/{booking}/passengers',
        summary: 'Add a passenger to a booking',
        security: [['sanctum' => []]],
        tags: ['Passengers'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['first_name', 'last_name', 'birth_date'],
                properties: [
                    new OA\Property(property: 'first_name', type: 'string', example: 'Ali'),
                    new OA\Property(property: 'last_name', type: 'string', example: 'Rezaei'),
                    new OA\Property(property: 'national_id', type: 'string', example: '1234567890', nullable: true),
                    new OA\Property(property: 'passport_number', type: 'string', example: 'A12345678', nullable: true),
                    new OA\Property(property: 'birth_date', type: 'string', example: '1990-05-15'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Passenger added'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]

    public function store(StorePassengerRequest $request, Booking $booking): JsonResponse
    {
        Gate::authorize('update', $booking);
        $passenger = $this->passengerService->create($booking, $request->validated());

        return response()->json([
            'data' => new PassengerResource($passenger)
        ], 201);
    }

    #[OA\Delete(
        path: '/api/bookings/{booking}/passengers/{passenger}',
        summary: 'Remove a passenger from a booking',
        security: [['sanctum' => []]],
        tags: ['Passengers'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'passenger', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Passenger removed'),
            new OA\Response(response: 422, description: 'Cannot remove passenger with issued ticket'),
        ]
    )]

    public function destroy(Booking $booking, Passenger $passenger): JsonResponse
    {
        Gate::authorize('update', $booking);
        $this->passengerService->delete($passenger);

        return response()->json(null, 204);
    }
}