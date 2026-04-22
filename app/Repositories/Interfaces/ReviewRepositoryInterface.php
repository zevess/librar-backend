<?php

namespace App\Repositories\Interfaces;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ReviewRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Review;

    public function findByBook(int $bookId): Collection;

    public function findByUser(int $userId): Collection;

    public function findByBookAndUser(int $bookId, int $userId): ?Review;

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator;

    public function create(array $data): Review;

    public function update(Review $review, array $data): Review;

    public function delete(Review $review): bool;

    public function restore(Review $review): bool;
}
