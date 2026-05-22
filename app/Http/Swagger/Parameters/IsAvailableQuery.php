<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;


#[OA\Parameter(
    parameter: 'IsAvailableQuery',
    name: 'isAvailable',
    in: 'query',
    description: 'Доступность',
    schema: new OA\Schema(type: 'boolean')
)]
class IsAvailableQuery
{
}