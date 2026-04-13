<?php

namespace App\Repositories\Interfaces;

use App\Models\Publisher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PublisherRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Publisher;

    public function find(int $id): ?Publisher;

    public function findBySlug(string $slug): ?Publisher;

    public function findBySlugAndId(string $slug, int $id): ?Publisher;

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator;

    public function getBySlug(?string $slug): Collection;

    public function update(Publisher $publisher, array $data): Publisher;

    public function delete(Publisher $publisher): bool;

    public function restore(Publisher $publisher): bool;
}
