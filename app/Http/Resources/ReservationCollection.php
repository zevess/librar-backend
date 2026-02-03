<?php

namespace App\Http\Resources;

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
                    'reserved_by' => $reservation->reserved_by,
                    'status' => $reservation->status,
                ];
            })
        ];
    }
}
