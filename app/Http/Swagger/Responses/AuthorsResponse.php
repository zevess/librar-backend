<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'AuthorsResponse',
    description: 'Авторы',
    content: new OA\JsonContent(
        ref: '#/components/schemas/AuthorCollection'
    )
)]
class AuthorsResponse
{
}