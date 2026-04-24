<?php

namespace App\Http\Controllers;

use App\Http\Resources\Book\BookSummaryCollection;
use App\Http\Resources\User\UserPublicCollection;
use App\Services\Interfaces\SubscriptionServiceInterface;
use Illuminate\Http\JsonResponse;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionServiceInterface $subscriptionService
    ) {
    }

    public function showByUser(int $userId): BookSummaryCollection
    {
        $userSubscriptions = $this->subscriptionService->getUserSubscriptions($userId);
        return new BookSummaryCollection($userSubscriptions);
    }

    public function showByBook(int $bookId): UserPublicCollection
    {
        $bookSubscribers = $this->subscriptionService->getBookSubscribers($bookId);
        return new UserPublicCollection($bookSubscribers);
    }

    public function store(int $bookId): JsonResponse
    {
        $userId = auth()->id();
        $this->subscriptionService->subscribe($userId, $bookId);
        return response()->json([
            'message' => "Вы подписались к книге"
        ]);
    }

    public function destroy(int $bookId): JsonResponse
    {
        $userId = auth()->id();
        $this->subscriptionService->unsubscribe($userId, $bookId);
        return response()->json([
            'message' => "Вы отписались от книги"
        ]);
    }
}
