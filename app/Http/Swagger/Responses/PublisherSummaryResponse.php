<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'PublisherSummaryResponse',
    description: 'Издатель',
    content: new OA\JsonContent(
        ref: '#/components/schemas/PublisherSummaryResource'
    )
)]
class PublisherSummaryResponse
{
}