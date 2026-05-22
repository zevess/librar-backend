<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;


#[OA\Parameter(
    parameter: 'StatusQuery',
    name: 'status',
    in: 'query',
    description: 'Статус',
    schema: new OA\Schema(type: 'string', enum: ['reserved', 'available', ''])
)]
class StatusQuery
{
}