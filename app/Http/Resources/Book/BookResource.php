<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorResource;
use App\Http\Resources\BaseResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Genre\GenreCollection;
use App\Http\Resources\Publisher\PublisherResource;
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
            'author' => new AuthorResource($this->author),
            'publisher' => new PublisherResource($this->publisher),
            'category' => new CategoryResource($this->category),
            'genres' => new GenreCollection($this->genres),
            'isDeleted' => (bool) $this->deleted_at,
            'isAvailable' => $this->activeReservations->isEmpty(),
            'isSubscribed' => $this->subscribers->where('pivot.user_id', $userId)->isNotEmpty(),
        ];
    }
}
