<?php

namespace App\Services\Interfaces;

use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

interface BookServiceInterface
{
    public function getAll(): Collection;

    public function getPaginated(?array $data, ?bool $includeTrashed = false, ?bool $includeInactive = false): LengthAwarePaginator;

    public function getByQuery(?string $query): Collection;

    public function getById(int $id): ?Book;

    public function getBySlugAndId(string $slug, int $id): ?Book;

    public function getByAuthorId(int $authorId): Collection;

    public function create(array $data): Book;

    public function update(int $id, array $data): ?Book;

    public function delete(int $id): bool;

    public function restore(int $id): bool;

    public function import(UploadedFile $file): array;
}
