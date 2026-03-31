<?php

namespace App\Repositories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function all(): Collection
    {
        return Subscription::get();
    }

    public function create(array $data): Subscription
    {
        return Subscription::create($data);
    }

    public function find(int $id): Subscription
    {
        return Subscription::findOrFail($id);
    }

    public function findByUser(int $userId): Collection
    {
        return Subscription::with(['book', 'user'])->where('user_id', $userId)->get();
    }

    public function findByBook(int $bookId): Collection
    {
        return Subscription::with(['user'])->where('book_id', $bookId)->get();
    }

    public function update(Subscription $subscription, array $data): Subscription
    {
        $subscription->update($data);
        return $subscription;
    }

    public function delete(Subscription $subscription): bool
    {
        return $subscription->delete();
    }
}
