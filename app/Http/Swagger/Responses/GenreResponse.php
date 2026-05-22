<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'GenreResponse',
    description: 'Жанр',
    content: new OA\JsonContent(
        ref: '#/components/schemas/GenreResource'
    )
)]
class GenreResponse
{
}