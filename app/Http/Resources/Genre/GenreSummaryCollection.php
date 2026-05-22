<?php

namespace App\Http\Resources\Genre;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'GenreSummaryCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список жанров',
            items: new OA\Items(
                ref: '#/components/schemas/GenreSummaryResource'
            )
        ),
    ],
    type: 'object'
)]
class GenreSummaryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($genre) {
                return [
                    'id' => $genre->id,
                    'name' => $genre->name,
                    'slug' => $genre->slug,
                ];
            })
        ];
    }
}
