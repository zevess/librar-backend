<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Subscription;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Services\Interfaces\SubscriptionServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class SubscriptionService implements SubscriptionServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->subscriptionRepository->all();
    }

    public function getUserSubscriptions(int $userId): Collection
    {
        $follows = $this->subscriptionRepository->findByUser($userId);
        $books = $follows->pluck('book');
        if (!$books) {
            throw new ApiException("Подписки не найдены");
        }
        Gate::authorize('view', $follows->first());

        return $books;
    }

    public function getBookSubscribers(int $bookId): Collection
    {
        $subscribers = $this->subscriptionRepository->findByBook($bookId)->pluck('user');
        if (!$subscribers) {
            throw new ApiException("Подписчики не найдены");
        }
        return $subscribers;
    }

    public function getByUserAndBook(int $userId, int $bookId): Subscription
    {
        $subscription = $this->subscriptionRepository->findByUser($userId)->where($bookId)->first();
        return $subscription;
    }

    public function subscribe(int $userId, int $bookId): Subscription
    {
        $inSubscribed = $this->subscriptionRepository->findByUser($userId)->where('book_id', $bookId)->first();
        if ($inSubscribed) {
            throw new ApiException('Вы уже подписаны');
        }
        $data['user_id'] = $userId;
        $data['book_id'] = $bookId;

        return $this->subscriptionRepository->create($data);
    }

    public function unsubscribe(int $userId, int $bookId): bool
    {
        $isSubscribed = $this->subscriptionRepository->findByUser($userId)->where('book_id', $bookId)->first();
        if (!$isSubscribed) {
            throw new ApiException('Вы не подписаны');
        }
        return $this->subscriptionRepository->delete($isSubscribed);
    }
}
