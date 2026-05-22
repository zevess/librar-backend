<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookSummaryResource',
    properties: [
        new OA\Property(
            property: 'id',
            type: 'integer',
        ),
        new OA\Property(
            property: 'title',
            type: 'string',
        ),
        new OA\Property(
            property: 'slug',
            type: 'string',
        ),
        new OA\Property(
            property: 'image',
            type: 'string',
        ),
        new OA\Property(
            property: 'author',
            ref: '#/components/schemas/AuthorSummaryResource'
        ),
        new OA\Property(
            property: 'isAvailable',
            type: 'boolean',
        ),
    ],
    type: 'object'
)]


class BookSummaryResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->image,
            'author' => new AuthorSummaryResource($this->whenLoaded('author')),
            'isAvailable' => $this->activeReservations ? $this->activeReservations->isEmpty() : false,
        ];
    }
}
