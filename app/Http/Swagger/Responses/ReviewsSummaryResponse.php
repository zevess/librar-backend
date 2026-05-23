<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'ReviewsSummaryResponse',
    description: 'Отзывы',
    content: new OA\JsonContent(
        ref: '#/components/schemas/ReviewSummaryCollection'
    )
)]
class ReviewsSummaryResponse
{
}