<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Passenger;
use Illuminate\Validation\ValidationException;

class PassengerService
{
    public function create(Booking $booking, array $data): Passenger
    {
        if ($booking->status === 'cancelled') {
            throw ValidationException::withMessages([
                'booking' => 'Cannot add passengers to a cancelled booking.',
            ]);
        }

        return $booking->passengers()->create($data);
    }

    public function delete(Passenger $passenger): void
    {

        if ($passenger->ticket) {
            throw ValidationException::withMessages([
                'passenger' => 'Cannot remove a passenger with an issued ticket.',
            ]);
        }

        $passenger->delete();
    }
}