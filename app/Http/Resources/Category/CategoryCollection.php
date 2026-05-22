<?php

namespace App\Http\Resources\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'CategoryCollection',
    properties: [
        new OA\Property(
            property: 'data',
            type: 'array',
            description: 'Список категорий',
            items: new OA\Items(
                ref: '#/components/schemas/CategoryResource'
            )
        ),
    ],
    type: 'object'
)]

class CategoryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'isDeleted' => (bool) $category->deleted_at,
                ];
            })
        ];
    }
}
