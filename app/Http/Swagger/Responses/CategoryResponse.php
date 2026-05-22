<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'CategoryResponse',
    description: 'Категория',
    content: new OA\JsonContent(
        ref: '#/components/schemas/CategoryResource'
    )
)]
class CategoryResponse
{
}