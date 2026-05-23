<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'BookIdQuery',
    name: 'bookId',
    in: 'query',
    description: 'bookId ресурса',
    schema: new OA\Schema(type: 'integer')
)]
class BookIdQuery
{
}