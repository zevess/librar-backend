<?php

namespace App\Repositories\Interfaces;

use App\Models\Genre;
use Illuminate\Database\Eloquent\Collection;

interface GenreRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Genre;

    public function find(int $id): Genre;

    public function update(Genre $genre, array $data): Genre;

    public function delete(Genre $genre): bool;
}
