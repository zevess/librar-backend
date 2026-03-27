<?php

namespace App\Repositories;

use App\Models\Follow;
use App\Models\User;
use App\Repositories\Interfaces\FollowRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class FollowRepository implements FollowRepositoryInterface
{
    public function all(): Collection
    {
        return Follow::get();
    }

    public function create(array $data): Follow
    {
        return Follow::create($data);
    }

    public function find(int $id): Follow
    {
        return Follow::findOrFail($id);
    }

    public function findByUser(int $userId): Collection
    {
        return Follow::with(['book', 'user'])->where('user_id', $userId)->get();
    }

    public function findByBook(int $bookId): Collection
    {
        return Follow::with(['book', 'user'])->where('book_id', $bookId)->get();
    }

    public function update(Follow $follow, array $data): Follow
    {
        $follow->update($data);
        return $follow;
    }

    public function delete(Follow $follow): bool
    {
        return $follow->delete();
    }
}
