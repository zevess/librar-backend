<?php

namespace App\Http\Resources\Author;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AuthorCollection extends ResourceCollection
{
   
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($author) {
                return [
                    'id' => $author->id,
                    'name' => $author->name,
                    'slug' => $author->slug,
                    'description' => $author->description,
                ];
            })
        ];
    }
}
