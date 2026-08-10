<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'GenresResponse',
    description: 'Жанры',
    content: new OA\JsonContent(
        ref: '#/components/schemas/GenreCollection'
    )
)]
class GenresResponse
{
}