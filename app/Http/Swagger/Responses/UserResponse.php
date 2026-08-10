<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'UserResponse',
    description: 'Пользователь',
    content: new OA\JsonContent(
        ref: '#/components/schemas/UserResource'
    )
)]
class UserResponse
{
}