<?php

namespace App\Repositories\Interfaces;

use App\Models\Follow;
use Illuminate\Database\Eloquent\Collection;

interface FollowRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Follow;

    public function find(int $id): Follow;

    public function findByUser(int $userId): Collection;

    public function findByBook(int $bookId): Collection;

    public function update(Follow $follow, array $data): Follow;

    public function delete(Follow $follow): bool;
}
