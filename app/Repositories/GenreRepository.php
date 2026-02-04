<?php

namespace App\Repositories;

use App\Models\Genre;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GenreRepository implements GenreRepositoryInterface
{
    public function all(): Collection
    {
        return Genre::get();
    }

    public function create(array $data): Genre
    {
        return Genre::create($data);
    }

    public function find(int $id): Genre
    {
        return Genre::findOrFail($id);
    }

    public function update(Genre $genre, array $data): Genre
    {
        $genre->update($data);
        return $genre;
    }

    public function delete(Genre $genre): bool
    {
        return $genre->delete();
    }
}
