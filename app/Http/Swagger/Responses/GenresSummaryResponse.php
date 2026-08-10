<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'GenresSummaryResponse',
    description: 'Жанры',
    content: new OA\JsonContent(
        ref: '#/components/schemas/GenreSummaryCollection'
    )
)]
class GenresSummaryResponse
{
}