<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'BookCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список книг',
            items: new OA\Items(
                ref: '#/components/schemas/BookResource'
            )
        ),
    ],
    type: 'object'
)]

class BookCollection extends ResourceCollection
{
    public $collects = BookResource::class;

}
