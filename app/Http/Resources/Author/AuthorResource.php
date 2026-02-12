<?php

namespace App\Http\Resources\Author;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Book\BookCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends BaseResource
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
            'name'=> $this->name,
            'slug'=> $this->slug,
            'description' => $this->description,
            'books' => new BookCollection($this->whenLoaded('books')),
        ];
    }
}
