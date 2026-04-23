<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Book\BookSummaryResource;
use App\Http\Resources\User\UserPublicResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewSummaryResource extends JsonResource
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
            'text' => $this->text,
            'rating' => $this->rating,
            'created_at' => $this->created_at,
            // 'user' => new UserPublicResource($this->whenLoaded('user')),
            'book' => new BookSummaryResource($this->book),
        ];
    }
}
