<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorSummaryResource;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Category\CategorySummaryResource;
use App\Http\Resources\Genre\GenreSummaryCollection;
use App\Http\Resources\Publisher\PublisherSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'BookResource',
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
            property: 'description',
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
            property: 'publisher',
            ref: '#/components/schemas/PublisherSummaryResource'
        ),
        new OA\Property(
            property: 'genres',
            ref: '#/components/schemas/GenreSummaryCollection'
        ),
        new OA\Property(
            property: 'isAvailable',
            type: 'boolean',
        ),
        new OA\Property(
            property: 'isSubscribed',
            type: 'boolean',
        ),
        new OA\Property(
            property: 'isDeleted',
            type: 'boolean',
        ),
    ],
    type: 'object'
)]

class BookResource extends BaseResource
{

    public function toArray(Request $request): array
    {
        $userId = Auth::guard('sanctum')->id();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'author' => new AuthorSummaryResource($this->whenLoaded('author')),
            'publisher' => new PublisherSummaryResource($this->whenLoaded('publisher')),
            'category' => new CategorySummaryResource($this->whenLoaded('category')),
            'genres' => new GenreSummaryCollection($this->whenLoaded('genres')),
            'isAvailable' => $this->activeReservations ? $this->activeReservations->isEmpty() : false,
            'isSubscribed' => $this->subscribers ? $this->subscribers->where('pivot.user_id', $userId)->isNotEmpty() : false,
            'isDeleted' => (bool) $this->deleted_at,
            'isActive'=> $this->is_active
        ];
    }
}
