<?php

namespace App\Repositories\Interfaces;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function find(int $id): ?User;

    public function findByEmail(string $email): ?User;
    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator;

    public function updateRole(int $id, UserRole $role): ?User;

    public function create(array $data): User;

    public function update(User $user, array $data): ?User;

    public function delete(User $user): bool;

    public function restore(User $user): bool;
}
