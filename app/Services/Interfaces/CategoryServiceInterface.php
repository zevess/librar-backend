<?php

namespace App\Services\Interfaces;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): Category;

    public function getBySlug(string $slug): Category;

    public function create(array $data): Category;

    public function getPaginated(?array $data, ?bool $includeTrashed = false): LengthAwarePaginator;

    public function getAdminFiltered(?array $data): Collection;

    public function getByQuery(?string $query): Collection;

    public function update(int $id, array $data): Category;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

}
