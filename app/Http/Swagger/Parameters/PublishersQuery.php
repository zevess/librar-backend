<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;


#[OA\Parameter(
    parameter: 'PublishersQuery',
    name: 'publishers[]',
    in: 'query',
    description: 'Издатели',
    schema: new OA\Schema(
        type: 'array',
        items: new OA\Items(type: 'integer')
    ),
    style: 'form',
    explode: true
)]
class PublishersQuery
{
}