<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'PublishersSummaryResponse',
    description: 'Издатели',
    content: new OA\JsonContent(
        ref: '#/components/schemas/PublisherSummaryCollection'
    )
)]
class PublishersSummaryResponse
{
}