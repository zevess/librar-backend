<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'UserIdQuery',
    name: 'userId',
    in: 'query',
    description: 'userId ресурса',
    schema: new OA\Schema(type: 'integer')
)]
class UserIdQuery
{
}