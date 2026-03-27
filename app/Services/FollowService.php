<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Follow;
use App\Repositories\Interfaces\FollowRepositoryInterface;
use App\Services\Interfaces\FollowServiceInterface;
use Illuminate\Support\Collection;


class FollowService implements FollowServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private FollowRepositoryInterface $followRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->followRepository->all();
    }

    public function getUserFollows(int $userId): Collection
    {
        $follows = $this->followRepository->findByUser($userId);
        $books = $follows->pluck('book');
        if (!$books) {
            throw new ApiException("Книги не найдены");
        }
        return $books;
    }

    public function getBookFollowers(int $bookId): Collection
    {
        $followers = $this->followRepository->findByBook($bookId);
        $users = $followers->pluck('user');
        if (!$users) {
            throw new ApiException("Пользователи не найдены");
        }
        return $users;
    }

    public function getByUserAndBook(int $userId, int $bookId): Follow
    {
        $follow = $this->followRepository->findByUser($userId)->where($bookId)->first();
        return $follow;
    }

    public function follow(int $userId, int $bookId): Follow
    {
        $isFollowed = $this->followRepository->findByUser($userId)->where('book_id', $bookId)->first();
        if ($isFollowed) {
            throw new ApiException('Вы уже подписаны');
        }
        $data['user_id'] = $userId;
        $data['book_id'] = $bookId;

        return $this->followRepository->create($data);
    }

    public function unfollow(int $userId, int $bookId): bool
    {
        $isFollowed = $this->followRepository->findByUser($userId)->where('book_id', $bookId)->first();
        if (!$isFollowed) {
            throw new ApiException('Вы не подписаны');
        }
        return $this->followRepository->delete($isFollowed);
    }
}
