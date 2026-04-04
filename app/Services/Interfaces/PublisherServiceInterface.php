<?php

namespace App\Services\Interfaces;

use App\Models\Publisher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PublisherServiceInterface
{
    public function getAll(): Collection;

    public function create(array $data): Publisher;

    public function getById(int $id): Publisher;

    public function getBySlug(string $slug): Publisher;

    public function getBySlugAndId(string $slug, int $id): Publisher;

    public function getPaginated(?array $data): LengthAwarePaginator;

    public function getByQuery(?string $query): Collection;

    public function update(int $id, array $data): Publisher;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

}
