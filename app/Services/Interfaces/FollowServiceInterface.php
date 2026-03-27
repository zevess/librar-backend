<?php

namespace App\Services\Interfaces;

use App\Models\Follow;
use Illuminate\Support\Collection;


interface FollowServiceInterface
{
    public function getAll(): Collection;

    public function follow(int $userId, int $bookId): Follow;

    public function unfollow(int $userId, int $bookId): bool;

    public function getUserFollows(int $userId): Collection;

    public function getBookFollowers(int $bookId): Collection;

    public function getByUserAndBook(int $userId, int $bookId): Follow;
}
