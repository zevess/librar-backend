<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'SortQuery',
    name: 'sort',
    in: 'query',
    description: 'Сортировка',
    schema: new OA\Schema(type: 'string')
)]
class SortQuery
{
}