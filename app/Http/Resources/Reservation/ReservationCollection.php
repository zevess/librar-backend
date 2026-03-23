<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReservationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($reservation) {
                return [
                    'id' => $reservation->id,
                    'book' => $reservation->book,
                    'reservedBy' => $reservation->reservedBy,
                    'status' => $reservation->status,
                    'expiresAt' => $reservation->expires_at,
                    'issuedAt' => $reservation->issued_at,
                    'acceptedAt' => $reservation->accepted_at
                ];
            })
        ];
    }
}
