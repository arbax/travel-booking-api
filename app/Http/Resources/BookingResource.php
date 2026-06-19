<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PassengerResource;
use App\Http\Resources\PaymentResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
        'id' => $this->id,
        'user_id' => $this->user_id,
        'customer_name' => $this->customer_name,
        'customer_email' => $this->customer_email,
        'flight_number' => $this->flight_number,
        'origin' => $this->origin,
        'destination' => $this->destination,
        'travel_date' => $this->travel_date->format('Y-m-d'),
        'departure_time' => $this->departure_time,
        'arrival_time' => $this->arrival_time,
        'seat_class' => $this->seat_class,
        'status' => $this->status,
        'total_price' => $this->total_price,
        'passengers' => PassengerResource::collection($this->whenLoaded('passengers')),
        'payment' => new PaymentResource($this->whenLoaded('payment')),
        'created_at' => $this->created_at->toIso8601String(),
        'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}