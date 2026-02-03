<?php

namespace App\Http\Resources;

use App\Models\User;
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
            'book' => new BookResource($this->whenLoaded('book')),
            'reserved_by' => new UserResource($this->whenLoaded('reservedBy')),
            'status' => $this->status,
        ];
    }
}
