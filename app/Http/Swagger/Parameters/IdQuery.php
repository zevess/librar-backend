<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'IdQuery',
    name: 'id',
    in: 'query',
    description: 'id ресурса',
    schema: new OA\Schema(type: 'integer')
)]
class IdQuery
{
}