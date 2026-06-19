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
            'status' => $this->status,
            'total_price' => $this->total_price, 
            'travel_date' => $this->travel_date->format('Y-m-d'), 
            
            'passengers' => PassengerResource::collection($this->whenLoaded('passengers')),
            
            'payment' => new PaymentResource($this->whenLoaded('payment')),
            
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}