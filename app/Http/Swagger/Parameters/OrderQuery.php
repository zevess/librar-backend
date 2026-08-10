<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'OrderQuery',
    name: 'order',
    in: 'query',
    description: 'Порядок',
    schema: new OA\Schema(type: 'string', default: 'desc')
)]
class OrderQuery
{
}