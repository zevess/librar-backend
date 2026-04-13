<?php

namespace App\Http\Resources\Publisher;

use App\Http\Resources\BaseResource;
use App\Http\Resources\Book\BookCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublisherResource extends BaseResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'isDeleted' => (bool) $this->deleted_at,
            'books' => new BookCollection($this->whenLoaded('books')),
        ];
    }
}
