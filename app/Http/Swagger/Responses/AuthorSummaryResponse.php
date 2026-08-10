<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'AuthorSummaryResponse',
    description: 'Автор',
    content: new OA\JsonContent(
        ref: '#/components/schemas/AuthorSummaryResource'
    )
)]
class AuthorSummaryResponse
{
}