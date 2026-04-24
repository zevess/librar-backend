<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{

    public function find(int $id): ?User
    {
        return User::findOrFail($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where("email", $email)->first();
    }

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator
    {
        $userId = $data['id'] ?? '';
        $search = $data['q'] ?? '';
        $email = $data['email'] ?? '';
        $role = $data['role'] ?? '';
        $result = User::when($userId, function ($query) use ($userId) {
            $query->where('id', $userId);
        })->when($search, function ($query) use ($search) {
            $query->where('name', $search);
        })->when($email, function ($query) use ($email) {
            $query->where('email', $email);
        })->when($role, function ($query) use ($role) {
            $query->where('role', $role);
        })->withTrashed()->orderBy('role');
        return $result->paginate($perPage)->withQueryString();
    }

    public function updateRole(int $id, UserRole $role): User
    {
        $user = User::findOrFail($id);
        $user->update([
            'role' => $role->value
        ]);
        return $user;
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): ?User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function restore(User $user): bool
    {
        return $user->restore();
    }
}
