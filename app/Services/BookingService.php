<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class BookingService
{
    public function getAll(User $user): LengthAwarePaginator
    {
        $query = Booking::with(['user', 'passengers', 'payment'])->latest();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query->paginate(15);
    }

    public function create(array $data, User $user): Booking
    {
        return Booking::create([
            ...$data,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->fill($data)->save();

        return $booking;
    }

    public function cancel(Booking $booking): Booking
    {
        if ($booking->status === 'cancelled') {
            throw ValidationException::withMessages([
                'booking' => 'This booking is already cancelled.',
            ]);
        }

        $booking->update(['status' => 'cancelled']);
        
        return $booking;
    }
}