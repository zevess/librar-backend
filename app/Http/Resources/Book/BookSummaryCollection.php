<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorSummaryResource;
use App\Http\Resources\Category\CategorySummaryResource;
use App\Http\Resources\Publisher\PublisherSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookSummaryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'image' => $book->image,
                    'author' => $book->author ? new AuthorSummaryResource($book->author) : null,
                    'isAvailable' => $book->activeReservations ? $book->activeReservations->isEmpty() : true,
                ];
            })
        ];
    }
}
