<?php

namespace App\Repositories\Interfaces;

use App\Models\Author;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface AuthorRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Author;

    public function findBySlugAndId(string $slug, int $id): ?Author;

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator;

    public function create(array $data): Author;

    public function update(Author $author, array $data): Author;

    public function delete(Author $author): bool;

    public function restore(Author $author): bool;
}
