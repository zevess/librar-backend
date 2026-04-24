<?php

namespace App\Repositories\Interfaces;

use App\Models\Subscription;
use Illuminate\Support\Collection;

interface SubscriptionRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): Subscription;

    public function findByUser(int $userId): Collection;

    public function findByBook(int $bookId): Collection;

    public function create(array $data): Subscription;

    public function update(Subscription $subscription, array $data): Subscription;

    public function delete(Subscription $subscription): bool;
}
