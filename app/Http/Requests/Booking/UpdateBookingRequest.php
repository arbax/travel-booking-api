<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'sometimes|string|max:255',
            'customer_email' => 'sometimes|email',
            'flight_number' => 'sometimes|string|max:20',
            'origin' => 'sometimes|string|size:3',
            'destination' => 'sometimes|string|size:3',
            'travel_date' => 'sometimes|date|after:today',
            'departure_time' => 'sometimes|date_format:H:i',
            'arrival_time' => 'sometimes|date_format:H:i',
            'seat_class' => 'sometimes|in:economy,business',
        ];
    }
}
