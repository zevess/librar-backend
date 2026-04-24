<?php

namespace App\Http\Resources\Reservation;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Book\BookSummaryResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class ReservationResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book' => new BookSummaryResource($this->whenLoaded('book')),
            'reservedBy' => new UserResource($this->whenLoaded('reservedBy')),
            'status' => $this->status,
            'expiresAt' => $this->expires_at,
            'issuedAt' => $this->issued_at,
            'acceptedAt' => $this->accepted_at
        ];
    }
}
