<?php

namespace App\Services\Interfaces;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

interface AuthorServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Author;

    public function create(array $data): Author;

    public function update(int $id, array $data): ?Author;

    public function delete(int $id): bool;
}
