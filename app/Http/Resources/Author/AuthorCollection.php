<?php

namespace App\Http\Resources\Author;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;
#[OA\Schema(
    schema: 'AuthorCollection',
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
        ),
        new OA\Property(
            property: 'name',
            type: 'string',
        ),
        new OA\Property(
            property: 'slug',
            type: 'string',
        ),
        new OA\Property(
            property: 'description',
            type: 'string',
        ),
        new OA\Property(
            property: 'isDeleted',
            type: 'boolean',
        ),
    ],
    type: 'object'
)]
class AuthorCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'slug' => $author->slug,
                    'description' => $author->description,
                    'isDeleted' => (bool) $author->deleted_at
                ];
            })
        ];
    }
}
