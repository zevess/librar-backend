<?php

namespace App\Repositories\Interfaces;

use App\Models\Genre;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface GenreRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Genre;

    public function find(int $id): Genre;

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator;

    public function getAdminFiltered(?array $data): Collection;

    public function getBySlug(?string $slug): Collection;

    public function findBySlug(string $slug): ?Genre;

    public function update(Genre $genre, array $data): Genre;

    public function delete(Genre $genre): bool;

    public function restore(Genre $genre): bool;

}
