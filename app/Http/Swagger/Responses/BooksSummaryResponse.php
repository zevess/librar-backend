<?php

namespace App\Http\Swagger\Responses;

use OpenApi\Attributes as OA;

#[OA\Response(
    response: 'BooksSummaryResponse',
    description: 'Книги',
    content: new OA\JsonContent(
        ref: '#/components/schemas/BookSummaryCollection'
    )
)]
class BooksSummaryResponse
{
}