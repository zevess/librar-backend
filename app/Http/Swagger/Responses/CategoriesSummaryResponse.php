<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'CategoriesSummaryResponse',
    description: 'Категории',
    content: new OA\JsonContent(
        ref: '#/components/schemas/CategorySummaryCollection'
    )
)]
class CategoriesSummaryResponse
{
}