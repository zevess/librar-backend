<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Book\BookResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($review) {
                return [
                    'id' => $review->id,
                    'text' => $review->text,
                    'rating' => $review->rating,
                    'created_at' => $review->created_at,
                    'user' => $review->user ? new UserResource($review->user) : null,
                    'book' => $review->book ? new BookResource($review->book) : null,
                    // 'user' => new UserResource($review->whenLoaded('user')),
                    // 'book' => new BookResource($review->whenLoaded('book')),
                    'isDeleted' => (bool) $review->deleted_at
                ];
            }),
        ];
    }
}
