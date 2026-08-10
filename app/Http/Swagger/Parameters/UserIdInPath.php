<?php

namespace App\Http\Swagger\Parameters;

use OpenApi\Attributes as OA;

#[OA\Parameter(
    parameter: 'UserIdInPath',
    name: 'userId',
    in: 'path',
    required: true,
    description: 'ID пользователя',
    schema: new OA\Schema(type: 'integer')
)]
class UserIdInPath
{
}