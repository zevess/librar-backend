<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'PublisherResponse',
    description: 'Издатель',
    content: new OA\JsonContent(
        ref: '#/components/schemas/PublisherResource'
    )
)]
class PublisherResponse
{
}