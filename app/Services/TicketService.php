<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Validation\ValidationException;

class TicketService
{
    public function issue(Booking $booking, Passenger $passenger): Ticket
    {
        if (!$booking->isConfirmed()) {
            throw ValidationException::withMessages([
                'booking' => 'Ticket can only be issued for confirmed bookings.',
            ]);
        }

        if ($passenger->ticket) {
            throw ValidationException::withMessages([
                'passenger' => 'Ticket already issued for this passenger.',
            ]);
        }

        return $passenger->ticket()->create([
            'booking_id' => $booking->id,
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
            'status' => 'issued',
            'issued_at' => now(),
        ]);
    }
}
