<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'RegisterResponse',
    description: 'Успешная регистрация',
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
class RegisterResponse
{
}