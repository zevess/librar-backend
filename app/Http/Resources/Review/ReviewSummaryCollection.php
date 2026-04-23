<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Book\BookSummaryResource;
use App\Http\Resources\User\UserPublicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewSummaryCollection extends ResourceCollection
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
                    'user' => $review->user ? new UserPublicResource($review->user) : null,
                    'book' => $review->book ? new BookSummaryResource($review->book) : null,
                ];
            }),
        ];
    }
}
