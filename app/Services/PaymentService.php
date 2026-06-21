<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function process(Booking $booking, array $data): Payment
    {
        if ($booking->payment) {
            throw ValidationException::withMessages([
                'booking' => 'This booking already has a payment.',
            ]);
        }

        if ($booking->status === 'cancelled') {
            throw ValidationException::withMessages([
                'booking' => 'Cannot process payment for a cancelled booking.',
            ]);
        }

        $payment = $booking->payment()->create([
            'amount' => $booking->total_price,
            'method' => $data['method'],
            'status' => 'paid',
            'reference_number' => 'REF-' . strtoupper(uniqid()),
            'paid_at' => now(),
        ]);

        $booking->update(['status' => 'confirmed']);

        return $payment;
    }
}