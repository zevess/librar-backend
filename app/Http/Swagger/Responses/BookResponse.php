<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'BookResponse',
    description: 'Книга',
    content: new OA\JsonContent(
        ref: '#/components/schemas/BookResource'
    )
)]
class BookResponse
{
}