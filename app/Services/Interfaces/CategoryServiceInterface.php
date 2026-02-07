<?php

namespace App\Services\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryServiceInterface
{
    public function getAll(): Collection;

    public function create(array $data): Category;

    public function getById(int $id): Category;

    public function getBySlug(string $slug): Category;

    public function update(int $id, array $data): Category;

    public function delete(int $id): bool;
}
