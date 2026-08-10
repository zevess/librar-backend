<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'BookIdInPath',
    name: 'bookId',
    in: 'path',
    required: true,
    description: 'ID книги',
    schema: new OA\Schema(type: 'integer')
)]
class BookIdInPath
{
}