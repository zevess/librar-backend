<?php

namespace App\Services\Interfaces;

use App\Models\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionServiceInterface
{
    public function getAll(): Collection;

    public function subscribe(int $userId, int $bookId): Subscription;

    public function unsubscribe(int $userId, int $bookId): bool;

    public function getUserSubscriptions(int $userId): Collection;

    public function getBookSubscribers(int $bookId): Collection;

    public function getByUserAndBook(int $userId, int $bookId): Subscription;
}
