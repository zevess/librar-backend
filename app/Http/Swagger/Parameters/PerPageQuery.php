<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;


#[OA\Parameter(
    parameter: 'PerPageQuery',
    name: 'perPage',
    in: 'query',
    description: 'Сколько страниц',
    schema: new OA\Schema(type: 'integer', default: 10)
)]
class PerPageQuery
{
}