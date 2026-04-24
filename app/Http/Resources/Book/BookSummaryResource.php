<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorSummaryResource;
use App\Http\Resources\Category\CategorySummaryResource;
use App\Http\Resources\Genre\GenreSummaryCollection;
use App\Http\Resources\Publisher\PublisherSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
