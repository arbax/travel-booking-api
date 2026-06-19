<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ticket_number' => $this->ticket_number,
            'seat_number' => $this->seat_number,
            'status' => $this->status,
            'issued_at' => $this->issued_at?->toIso8601String(),
        ];
    }
}