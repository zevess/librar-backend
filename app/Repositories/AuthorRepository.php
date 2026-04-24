<?php

namespace App\Repositories;

use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class AuthorRepository implements AuthorRepositoryInterface
{
    public function all(): Collection
    {
        return Author::latest()->get();
    }

    public function find(int $id): ?Author
    {
        return Author::with(['books', 'books.author', 'books.activeReservations'])->find($id);
    }

    public function findBySlugAndId(string $slug, int $id): ?Author
    {
        return Author::with(['books', 'books.author', 'books.activeReservations'])->where('id', $id)->where('slug', $slug)->first();
    }

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $result = Author::when($id, fn($q) => $q->where('id', $id))->when($search, fn($q) => $q->where('slug', 'like', "%{$search}%"))->withTrashed($includeTrashed);
        return $result->paginate($perPage)->withQueryString();
    }

    public function getBySlug(?string $slug): Collection
    {
        return Author::query()->where('slug', 'like', "%{$slug}%")->take(10)->get();
    }

    public function create(array $data): Author
    {
        return Author::create($data);
    }

    public function update(Author $author, array $data): Author
    {
        $author->update($data);
        return $author;
    }

    public function delete(Author $author): bool
    {
        return $author->delete();
    }

    public function restore(Author $author): bool
    {
        return $author->restore();
    }

}
