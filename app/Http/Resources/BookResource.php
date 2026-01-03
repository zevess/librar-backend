<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
            'author' => new AuthorResource($this->whenLoaded('author')),
        ];
    }
}
