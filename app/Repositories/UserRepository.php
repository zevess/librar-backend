<?php

namespace App\Repositories;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface
{
    
    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return User::latest()->paginate($perPage);
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where("email", $email)->first();
    }

    public function updateRole(int $id, UserRole $role): User
    {
        $user = User::findOrFail($id);
        $user->update([
            'role' => $role->value
        ]);
        return $user;

        
        // return User::where('id', $id)->update([
        //     'role' => $role->value
        // ]);
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
