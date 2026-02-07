<?php

namespace App\Services\Interfaces;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

interface GenreServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): Genre;

    public function create(string $genreName): Genre;

    public function update(int $id, array $data): Genre;

    public function attachToBook(int $bookId, array $genres): bool;

    public function detachFromBook(int $bookId, array $genres): bool;

    public function delete(int $id): bool;
}
