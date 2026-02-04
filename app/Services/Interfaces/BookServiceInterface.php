<?php

namespace App\Services\Interfaces;

use App\Enums\BookStatus;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface
{
    public function getAll(): Collection;

    public function getPaginated(?string $search, int $perPage): LengthAwarePaginator;

    public function getById(int $id): ?Book;

    public function getByAuthorId(int $authorId): Collection;

    public function create(array $data): Book;

    public function update(int $id, array $data): ?Book;

    public function delete(int $id): bool;

    public function restore(int $id): bool;
}
