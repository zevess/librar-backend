<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'ReviewsResponse',
    description: 'Отзывы',
    content: new OA\JsonContent(
        ref: '#/components/schemas/ReviewCollection'
    )
)]
class ReviewsResponse
{
}