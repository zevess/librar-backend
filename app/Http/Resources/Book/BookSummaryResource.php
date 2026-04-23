<?php

namespace App\Http\Resources\Book;

use App\Http\Resources\Author\AuthorSummaryResource;
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
            // 'description' => $this->description,
            'image' => $this->image,
            // 'author' => $this->author ? new AuthorSummaryResource($this->author) : null,
        ];
    }
}
