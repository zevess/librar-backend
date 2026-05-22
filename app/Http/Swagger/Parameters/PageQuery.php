<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;


#[OA\Parameter(
    parameter: 'PageQuery',
    name: 'page',
    in: 'query',
    description: 'Номер страницы',
    schema: new OA\Schema(type: 'integer', default: 1)
)]
class PageQuery
{
}