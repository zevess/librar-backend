<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'IdInPath',
    name: 'id',
    in: 'path',
    required: true,
    description: 'ID ресурса',
    schema: new OA\Schema(type: 'integer')
)]
class IdInPath
{
}