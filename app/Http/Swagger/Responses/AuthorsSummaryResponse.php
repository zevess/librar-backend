<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'AuthorsSummaryResponse',
    description: 'Авторы',
    content: new OA\JsonContent(
        ref: '#/components/schemas/AuthorSummaryCollection'
    )
)]
class AuthorsSummaryResponse
{
}