<?php

namespace App\Repositories\Interfaces;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;

interface PublisherRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Publisher;

    public function find(int $id): ?Publisher;

    public function findBySlug(string $slug): ?Publisher;

    public function update(Publisher $publisher, array $data): Publisher;

    public function delete(Publisher $publisher): bool;
}
