<?php

namespace App\Http\Requests\Booking;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'flight_number' => 'required|string|max:20',
            'origin' => 'required|string|size:3',
            'destination' => 'required|string|size:3',
            'travel_date' => 'required|date|after:today',
            'departure_time' => 'required|date_format:H:i',
            'arrival_time' => 'required|date_format:H:i',
            'seat_class' => 'required|in:economy,business',
            'total_price' => 'required|numeric|min:0',
        ];
    }
}
