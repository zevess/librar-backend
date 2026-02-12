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

class BookResource extends BaseResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title'=> $this->title,
            'slug'=> $this->slug,
            'description' => $this->description,
            'author' => new AuthorResource($this->author),
            'publisher' => new PublisherResource($this->publisher),
            'category' => new CategoryResource($this->category),
            'genres' => new GenreCollection($this->genres),
            'isAwailable' => $this->activeReservations->isEmpty()
        ];
    }
}
