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
use OpenApi\Attributes as OA;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    #[OA\Get(
        path: '/api/bookings',
        summary: 'List all bookings',
        security: [['sanctum' => []]],
        tags: ['Bookings'],
        responses: [
            new OA\Response(response: 200, description: 'List of bookings'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
        ]
    )]

    public function index(Request $request): JsonResponse
    {
        $bookings = $this->bookingService->getAll($request->user());

        return response()->json(
            $bookings->through(fn($booking) => new BookingResource($booking))
        );
    }

    #[OA\Post(
        path: '/api/bookings',
        summary: 'Create a new booking',
        security: [['sanctum' => []]],
        tags: ['Bookings'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['customer_name', 'customer_email', 'flight_number', 'origin', 'destination', 'travel_date', 'departure_time', 'arrival_time', 'seat_class', 'total_price'],
                properties: [
                    new OA\Property(property: 'customer_name', type: 'string', example: 'Ali Rezaei'),
                    new OA\Property(property: 'customer_email', type: 'string', example: 'ali@test.com'),
                    new OA\Property(property: 'flight_number', type: 'string', example: 'IR455'),
                    new OA\Property(property: 'origin', type: 'string', example: 'THR'),
                    new OA\Property(property: 'destination', type: 'string', example: 'DXB'),
                    new OA\Property(property: 'travel_date', type: 'string', example: '2026-08-01'),
                    new OA\Property(property: 'departure_time', type: 'string', example: '08:30'),
                    new OA\Property(property: 'arrival_time', type: 'string', example: '11:00'),
                    new OA\Property(property: 'seat_class', type: 'string', enum: ['economy', 'business']),
                    new OA\Property(property: 'total_price', type: 'number', example: 2500000),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Booking created'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]

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

    #[OA\Get(
        path: '/api/bookings/{booking}',
        summary: 'Get a booking',
        security: [['sanctum' => []]],
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Booking details'),
            new OA\Response(response: 403, description: 'Forbidden'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]

    public function show(Booking $booking): JsonResponse
    {
        Gate::authorize('view', $booking);

        $booking->load(['passengers', 'payment']);

        return response()->json([
            'data' => new BookingResource($booking)
        ]);
    }

    #[OA\Put(
        path: '/api/bookings/{booking}',
        summary: 'Update a booking',
        security: [['sanctum' => []]],
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'customer_name', type: 'string', example: 'Ali Rezaei'),
                    new OA\Property(property: 'customer_email', type: 'string', example: 'ali@test.com'),
                    new OA\Property(property: 'flight_number', type: 'string', example: 'IR455'),
                    new OA\Property(property: 'origin', type: 'string', example: 'THR'),
                    new OA\Property(property: 'destination', type: 'string', example: 'DXB'),
                    new OA\Property(property: 'travel_date', type: 'string', example: '2026-08-01'),
                    new OA\Property(property: 'departure_time', type: 'string', example: '08:30'),
                    new OA\Property(property: 'arrival_time', type: 'string', example: '11:00'),
                    new OA\Property(property: 'seat_class', type: 'string', enum: ['economy', 'business']),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Booking updated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]

    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        Gate::authorize('update', $booking);

        $booking = $this->bookingService->update($booking, $request->validated());

        return response()->json([
            'data' => new BookingResource($booking)
        ]);
    }

    #[OA\Post(
        path: '/api/bookings/{booking}/cancel',
        summary: 'Cancel a booking',
        security: [['sanctum' => []]],
        tags: ['Bookings'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Booking cancelled'),
            new OA\Response(response: 422, description: 'Already cancelled'),
        ]
    )]
    
    public function cancel(Booking $booking): JsonResponse
    {
        Gate::authorize('cancel', $booking);

        $booking = $this->bookingService->cancel($booking);

        return response()->json([
            'data' => new BookingResource($booking)
        ]);
    }
}
