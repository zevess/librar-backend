<?php

namespace App\Repositories;

use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
        return Review::with('user')->where('book_id', $bookId)->get();
    }
    public function findByUser(int $userId): Collection
    {
        return Review::where('user_id', $userId)->get();
    }

    public function findByBookAndUser(int $bookId, int $userId): ?Review
    {
        return Review::where('user_id', $userId)->where('book_id', $bookId)->first();
    }

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $user = $data['email'] ?? '';
        $userId = $data['userId'] ?? '';
        $bookId = $data['userId'] ?? '';
        $rating = $data['rating'] ?? '';

        $result = Review::with(['user', 'book'])
            ->when($id, fn($q) => $q->where('id', $id))
            ->when($search, fn($q) => $q->where('text', 'like', "%{$search}%"))
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($user, fn($query) => $query->whereHas('user', fn($q) => $q->where('email', 'like', "%{$user}%")))
            ->when($bookId, fn($q) => $q->where('book_id', $bookId))
            ->when($rating, fn($q) => $q->where('rating', $rating))
            ->withTrashed($includeTrashed);

        return $result->paginate($perPage)->withQueryString();
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

    public function restore(Review $review): bool
    {
        return $review->restore();
    }
}
