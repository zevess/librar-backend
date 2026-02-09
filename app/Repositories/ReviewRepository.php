<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewRepository implements ReviewRepositoryInterface
{
    public function all(): Collection
    {
        return Review::latest()->get();
    }
    public function find(int $id): ?Review
    {
        return Review::find($id);
    }
    public function findByBook(int $bookId): Collection
    {
        return Review::where('book_id', $bookId)->get();
    }
    public function findByUser(int $userId): Collection
    {
        return Review::where('user_id', $userId)->get();
    }

    public function findByBookAndUser(int $bookId, int $userId): ?Review
    {
        return Review::where('user_id', $userId)->where('book_id', $bookId)->first();
    }
    
    public function create(array $data): Review
    {
        return Review::create($data);
    }
    public function update(Review $review, array $data): Review
    {
        $review->update($data);
        return $review;
    }
    public function delete(Review $review): bool
    {
        return $review->delete();
    }
}
