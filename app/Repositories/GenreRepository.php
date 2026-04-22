<?php

namespace App\Repositories;

use App\Models\Genre;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $result = Genre::when($id, fn($q) => $q->where('id', $id))->when($search, fn($q) => $q->where('slug', 'like', "%{$search}%"))->withTrashed($includeTrashed);
        return $result->paginate($perPage)->withQueryString();
    }

    public function getAdminFiltered(?array $data): Collection
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $result = Genre::query()->when($id, fn($q) => $q->where('id', $id))->when($search, fn($q) => $q->where('slug', 'like', "%{$search}%"))->withTrashed()->get();
        return $result;
    }

    public function getBySlug(?string $slug): Collection
    {
        return Genre::query()->where('slug', 'like', "%{$slug}%")->get();
    }

    public function findBySlug(string $slug): ?Genre
    {
        return Genre::where('slug', $slug)->first();
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

    public function restore(Genre $genre): bool
    {
        return $genre->restore();
    }
}
