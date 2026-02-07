<?php

namespace App\Services\Interfaces;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;

interface PublisherServiceInterface
{
    public function getAll(): Collection;

    public function create(array $data): Publisher;

    public function getById(int $id): Publisher;

    public function getBySlug(string $slug): Publisher;

    public function update(int $id, array $data): Publisher;

    public function delete(int $id): bool;
}
