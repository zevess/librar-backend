<?php

namespace App\Http\Resources\Publisher;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'PublisherSummaryCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список издательств',
            items: new OA\Items(
                ref: '#/components/schemas/PublisherSummaryResource'
            )
        ),
    ],
    type: 'object'
)]
class PublisherSummaryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($publisher) {
                return [
                    'id' => $publisher->id,
                    'name' => $publisher->name,
                    'slug' => $publisher->slug,
                ];
            })
        ];
    }
}
