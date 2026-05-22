<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'AuthorResponse',
    description: 'Автор',
    content: new OA\JsonContent(
        ref: '#/components/schemas/AuthorResource'
    )
)]
class AuthorResponse
{
}