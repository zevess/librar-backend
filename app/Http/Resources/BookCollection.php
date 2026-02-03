<?php

namespace App\Http\Resources;

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
                    'description' => $book->description,
                    'status' => $book->status,
                    'reserved_by' => $book->reserved_by,
                ];
            })
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'total' => $this->collection->count()
            ]
        ];
    }
}
