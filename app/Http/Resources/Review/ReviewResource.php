<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Book\BookSummaryResource;
use App\Http\Resources\User\UserPublicResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends BaseResource
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
            'createdAt' => $this->created_at,
            'user' => new UserPublicResource($this->whenLoaded('user')),
            'book' => new BookSummaryResource($this->whenLoaded('book')),
            'isDeleted' => (bool) $this->deleted_at
        ];
    }
}
