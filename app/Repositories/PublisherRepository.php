<?php

namespace App\Repositories;

use App\Models\Publisher;
use App\Repositories\Interfaces\PublisherRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PublisherRepository implements PublisherRepositoryInterface
{
    public function all(): Collection
    {
        return Publisher::latest()->get();
    }

    public function create(array $data): Publisher
    {
        return Publisher::create($data);
    }

    public function find(int $id): ?Publisher
    {
        return Publisher::find($id);
    }

    public function findBySlug(string $slug): ?Publisher
    {
        return Publisher::where('slug', $slug)->first();
    }

    public function findBySlugAndId(string $slug, int $id): ?Publisher
    {
        return Publisher::with('books')->where('slug', $slug)->where('id', $id)->first();
    }

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $result = Publisher::when($id, fn($q) => $q->where('id', $id))->when($search, fn($q) => $q->where('slug', 'like', "%{$search}%"));
        return $result->paginate($perPage)->withQueryString();
    }

    public function update(Publisher $publisher, array $data): Publisher
    {
        $publisher->update($data);
        return $publisher;
    }

    public function delete(Publisher $publisher): bool
    {
        return $publisher->delete();
    }

    public function restore(Publisher $publisher): bool
    {
        return $publisher->restore();
    }
}
