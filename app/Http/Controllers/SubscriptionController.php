<?php

namespace App\Http\Controllers;

use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\User\UserCollection;
use App\Services\Interfaces\SubscriptionServiceInterface;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionServiceInterface $subscriptionService
    ) {
    }

    public function showByUser(int $userId)
    {
        $userFollows = $this->subscriptionService->getUserSubscriptions($userId);
        return new BookCollection($userFollows);
    }

    public function showByBook(int $bookId)
    {
        $bookFollowers = $this->subscriptionService->getBookSubscribers($bookId);
        // return $bookFollowers;
        return new UserCollection($bookFollowers);
    }

    public function store(int $bookId)
    {
        $userId = auth()->id();
        $follow = $this->subscriptionService->subscribe($userId, $bookId);
        return $follow;
    }

    public function destroy(int $bookId)
    {
        $userId = auth()->id();
        $this->subscriptionService->unsubscribe($userId, $bookId);
        return response()->json([
            'message' => "Отписался"
        ]);
    }
}
