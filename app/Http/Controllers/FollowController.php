<?php

namespace App\Http\Controllers;

use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\User\UserCollection;
use App\Services\Interfaces\FollowServiceInterface;

class FollowController extends Controller
{
    public function __construct(
        private FollowServiceInterface $followService
    ) {
    }

    public function showByUser(int $userId)
    {
        $userFollows = $this->followService->getUserFollows($userId);
        return new BookCollection($userFollows);
    }

    public function showByBook(int $bookId)
    {
        $bookFollowers = $this->followService->getBookFollowers($bookId);
        return new UserCollection($bookFollowers);
    }

    public function store(int $bookId)
    {
        $userId = auth()->id();
        $follow = $this->followService->follow($userId, $bookId);
        return $follow;
    }

    public function destroy(int $bookId)
    {
        $userId = auth()->id();
        $this->followService->unfollow($userId, $bookId);
        return response()->json([
            'message' => "Отписался"
        ]);
    }
}
