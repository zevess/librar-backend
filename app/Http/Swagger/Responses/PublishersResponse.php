<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'PublishersResponse',
    description: 'Издатели',
    content: new OA\JsonContent(
        ref: '#/components/schemas/PublisherCollection'
    )
)]
class PublishersResponse
{
}