<?php

namespace App\Services\Interfaces;

use App\Models\Review;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ReviewServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Review;

    public function getByBook(int $bookId): Collection;

    public function getByUser(int $userId): Collection;

    public function getPaginated(?array $data, ?bool $includeTrashed = false): LengthAwarePaginator;

    public function create(int $userId, int $bookId, array $data): Review;

    public function update(int $id, array $data): Review;

    public function delete(int $id): bool;

    public function restore(int $id): bool;
}
