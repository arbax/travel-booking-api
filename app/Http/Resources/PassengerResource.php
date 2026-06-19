<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassengerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'national_id' => $this->national_id,
            'passport_number' => $this->passport_number,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'ticket' => new TicketResource($this->whenLoaded('ticket')),
        ];
    }
}