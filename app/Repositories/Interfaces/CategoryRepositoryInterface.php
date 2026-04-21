<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Category;

    public function find(int $id): ?Category;

    public function findBySlug(string $slug): ?Category;

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator;

    public function getBySlug(?string $slug): Collection;

    public function update(Category $category, array $data): Category;

    public function delete(Category $category): bool;

    public function restore(Category $category): bool;

}
