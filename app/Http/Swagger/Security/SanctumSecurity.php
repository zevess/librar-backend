<?php

namespace App\Http\Swagger\Security;
use OpenApi\Attributes as OA;


#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'введите access токен'
)]

class SanctumSecurity
{    
}
