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
                property: 'accessToken',
                type: 'string',
                description: 'accessToken',
            ),
            new OA\Property(
                property: 'refreshToken',
                type: 'string',
                description: 'refreshToken',
            )
        ]
    )
)]
class RegisterResponse
{
}