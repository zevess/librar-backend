<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'CategoriesResponse',
    description: 'Категории',
    content: new OA\JsonContent(
        ref: '#/components/schemas/CategoryCollection'
    )
)]
class CategoriesResponse
{
}