<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TicketResource;
use App\Models\Booking;
use App\Models\Passenger;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(private TicketService $ticketService) {}

    public function show(Booking $booking, Passenger $passenger): JsonResponse
    {
        $ticket = $passenger->ticket;

        if (!$ticket) {
            return response()->json(['message' => 'No ticket issued yet.'], 404);
        }

        return response()->json(['data' => new TicketResource($ticket)]);
    }

    public function issue(Booking $booking, Passenger $passenger): JsonResponse
    {
        $ticket = $this->ticketService->issue($booking, $passenger);

        return response()->json(['data' => new TicketResource($ticket)], 201);
    }
}
