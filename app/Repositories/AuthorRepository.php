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
        return Author::with('books')->find($id);
    }

    public function findBySlugAndId(string $slug, int $id): ?Author
    {
        return Author::with('books')->where('id', $id)->where('slug', $slug)->first();
    }

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $result = Author::when($id, fn($q) => $q->where('id', $id))->when($search, fn($q) => $q->where('slug', 'like', "%{$search}%"));
        return $result->paginate($perPage)->withQueryString();
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
