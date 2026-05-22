<?php

namespace App\Http\Resources\Author;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'AuthorSummaryCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список авторов',
            items: new OA\Items(
                ref: '#/components/schemas/AuthorSummaryResource'
            )
        ),
    ],
    type: 'object'
)]
class AuthorSummaryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'slug' => $author->slug,
                ];
            })
        ];
    }
}
