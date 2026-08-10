<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'ReviewResponse',
    description: 'Отзыв',
    content: new OA\JsonContent(
        ref: '#/components/schemas/ReviewResource'
    )
)]
class ReviewResponse
{
}