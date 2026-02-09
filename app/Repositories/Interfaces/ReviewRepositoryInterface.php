<?php

namespace App\Repositories\Interfaces;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Review;

    public function findByBook(int $bookId): Collection;

    public function findByUser(int $userId): Collection;

    public function findByBookAndUser(int $bookId, int $userId): ?Review;

    

    public function create(array $data): Review;

    public function update(Review $review, array $data): Review;

    public function delete(Review $review): bool;
}
