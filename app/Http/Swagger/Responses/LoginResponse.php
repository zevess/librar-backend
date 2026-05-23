<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'LoginResponse',
    description: 'Успешная аутентификация',
    content: new OA\JsonContent(
        type: 'object',
        properties: [
            new OA\Property(
                property: 'user',
                ref: '#/components/schemas/UserResource'
            ),
            new OA\Property(
                property: 'token',
                type: 'string',
                description: 'токен sanctum'
            )
        ]
    )
)]
class LoginResponse
{
}