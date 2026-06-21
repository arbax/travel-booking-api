<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Booking;
use App\Models\Passenger;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    #[OA\Get(
        path: '/api/bookings/{booking}/passengers/{passenger}/ticket',
        summary: 'Get ticket for a passenger',
        security: [['sanctum' => []]],
        tags: ['Tickets'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'passenger', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Ticket details'),
            new OA\Response(response: 404, description: 'No ticket issued yet'),
        ]
    )]

    public function show(Booking $booking, Passenger $passenger): JsonResponse
    {
        $ticket = $passenger->ticket;

        if (!$ticket) {
            return response()->json(['message' => 'No ticket issued yet.'], 404);
        }

        return response()->json(['data' => new TicketResource($ticket)]);
    }

    #[OA\Post(
        path: '/api/bookings/{booking}/passengers/{passenger}/ticket/issue',
        summary: 'Issue a ticket for a passenger',
        security: [['sanctum' => []]],
        tags: ['Tickets'],
        parameters: [
            new OA\Parameter(name: 'booking', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'passenger', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 201, description: 'Ticket issued'),
            new OA\Response(response: 422, description: 'Booking not confirmed or ticket already issued'),
        ]
    )]
    
    public function issue(Booking $booking, Passenger $passenger): JsonResponse
    {
        $ticket = $this->ticketService->issue($booking, $passenger);

        return response()->json(['data' => new TicketResource($ticket)], 201);
    }
}