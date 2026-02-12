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
                    'author' => new AuthorResource($book->author),
                    'publisher' => new PublisherResource($book->publisher),
                    'category' => new CategoryResource($book->category),
                    'genres' => new GenreCollection($book->genres)
                ];
            })
        ];
    }

    // public function with(Request $request): array
    // {
    //     return [
    //         'meta' => [
    //             'total' => $this->collection->count()
    //         ]
    //     ];
    // }
}
