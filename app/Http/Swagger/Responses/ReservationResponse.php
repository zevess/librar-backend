<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'ReservationResponse',
    description: 'Бронь',
    content: new OA\JsonContent(
        ref: '#/components/schemas/ReservationResource'
    )
)]
class ReservationResponse
{
}