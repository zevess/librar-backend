<?php

namespace App\Repositories;

use App\Models\Publisher;
use App\Repositories\Interfaces\PublisherRepositoryInterface;
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

    public function update(Publisher $publisher, array $data): Publisher
    {
        $publisher->update($data);
        return $publisher;
    }

    public function delete(Publisher $publisher): bool
    {
        return $publisher->delete();
    }
}
