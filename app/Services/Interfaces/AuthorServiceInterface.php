<?php

namespace App\Services\Interfaces;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AuthorServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Author;

    public function getBySlugAndId(string $slug, int $id): ?Author;

    public function getPaginated(?array $data, ?bool $includeTrashed = false): LengthAwarePaginator;

    public function getByQuery(?string $query): Collection;

    public function create(array $data): Author;

    public function update(int $id, array $data): ?Author;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

}
