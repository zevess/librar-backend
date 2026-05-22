<?php

namespace App\Http\Resources\Reservation;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'ReservationCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список броней',
            items: new OA\Items(
                ref: '#/components/schemas/ReservationResource'
            )
        ),
    ],
    type: 'object'
)]
class ReservationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = ReservationResource::class;
}
