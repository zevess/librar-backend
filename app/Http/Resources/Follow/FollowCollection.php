<?php

namespace App\Http\Resources\Follow;

use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FollowCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->transform(function ($follow) {
                return [
                    'user' => new UserResource($follow->user),
                    // 'book' => new BookResource($follow->books)
                ];
            }),
        ];
    }
}
