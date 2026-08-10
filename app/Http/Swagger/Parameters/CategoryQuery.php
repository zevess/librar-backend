<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;


#[OA\Parameter(
    parameter: 'CategoryQuery',
    name: 'category',
    in: 'query',
    description: 'Категория',
    schema: new OA\Schema(type: 'integer')
)]
class CategoryQuery
{
}