<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorSummaryResource;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Category\CategorySummaryResource;
use App\Http\Resources\Genre\GenreSummaryCollection;
use App\Http\Resources\Publisher\PublisherSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ];
    }
}
