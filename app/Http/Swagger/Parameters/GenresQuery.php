<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;


#[OA\Parameter(
    parameter: 'GenresQuery',
    name: 'genres[]',
    in: 'query',
    description: 'Жанры',
    schema: new OA\Schema(
        type: 'array',
        items: new OA\Items(type: 'integer')
    ),
    style: 'form',
    explode: true
)]
class GenresQuery
{
}