<?php

namespace App\Http\Resources\Reservation;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'book' => new BookResource($this->whenLoaded('book')),
            'reserved_by' => new UserResource($this->whenLoaded('reservedBy')),
            'status' => $this->status,
        ];
    }
}
