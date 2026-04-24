<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Book\BookSummaryResource;
use App\Http\Resources\User\UserPublicResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ReviewCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = ReviewResource::class;
}
