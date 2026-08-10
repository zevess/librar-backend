<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'EmailQuery',
    name: 'email',
    in: 'query',
    description: 'email ресурса',
    schema: new OA\Schema(type: 'string')
)]
class EmailQuery
{
}