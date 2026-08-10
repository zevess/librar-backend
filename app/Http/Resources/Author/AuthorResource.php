<?php

namespace App\Http\Resources\Author;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Book\BookSummaryCollection;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AuthorResource',
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
            property: 'books',
            ref: '#/components/schemas/BookSummaryCollection'
        ),
        new OA\Property(
            property: 'isDeleted',
            type: 'boolean',
        ),
    ],
    type: 'object'
)]

class AuthorResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'isDeleted' => (bool) $this->deleted_at,
            'books' => new BookSummaryCollection($this->whenLoaded('books')),
        ];
    }
}
