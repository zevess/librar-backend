<?php

namespace App\Services\Interfaces;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserServiceInterface
{
    public function getPaginated(int $perPage): LengthAwarePaginator;

    public function getById(int $id): ?User;
    
    public function getByEmail(string $email): ?User;

    public function changeRole(int $id, UserRole $role): bool;

    public function create(array $data): User;

    public function update(int $id, array $data): ?User;

    public function delete(int $id): bool;

    public function restore(int $id): bool;
}
