<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'ReservationsResponse',
    description: 'Брони',
    content: new OA\JsonContent(
        ref: '#/components/schemas/ReservationCollection'
    )
)]
class ReservationsResponse
{
}