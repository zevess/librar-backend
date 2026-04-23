<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorResource;
use App\Http\Resources\Author\AuthorSummaryResource;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategorySummaryResource;
use App\Http\Resources\Genre\GenreCollection;
use App\Http\Resources\Genre\GenreSummaryCollection;
use App\Http\Resources\Publisher\PublisherResource;
use App\Http\Resources\Publisher\PublisherSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
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
            'author' => $this->author ? new AuthorSummaryResource($this->author) : null,
            'publisher' => $this->publisher ? new PublisherSummaryResource($this->publisher) : null,
            'category' => $this->category ? new CategorySummaryResource($this->category) : null,
            'genres' => $this->genres ? new GenreSummaryCollection($this->genres) : null,
            'isDeleted' => (bool) $this->deleted_at,
            'isAvailable' => $this->activeReservations->isEmpty(),
            'isSubscribed' => $this->subscribers->where('pivot.user_id', $userId)->isNotEmpty(),
        ];
    }
}
