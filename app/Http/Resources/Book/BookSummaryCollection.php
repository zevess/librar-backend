<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'BookSummaryCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список книг',
            items: new OA\Items(
                ref: '#/components/schemas/BookSummaryResource'
            )
        ),
    ],
    type: 'object'
)]
class BookSummaryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = BookSummaryResource::class;
}
