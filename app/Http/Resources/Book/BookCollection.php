<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorResource;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Genre\GenreCollection;
use App\Http\Resources\Publisher\PublisherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'slug' => $book->slug,
                    'image' => $book->image,
                    'author' => $book->author ? new AuthorResource($book->author) : null,
                    'publisher' => $book->publisher ? new PublisherResource($book->publisher) : null,
                    'category' => $book->category ? new CategoryResource($book->category) : null,
                    'genres' => new GenreCollection($book->genres),
                    'isAvailable' => $book->activeReservations ? $book->activeReservations->isEmpty() : true,
                ];
            })
        ];
    }

}
