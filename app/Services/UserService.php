<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserService implements UserServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private UserRepositoryInterface $userRepository
    ){}

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return $this->userRepository->getPaginated($perPage);
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    public function changeRole(int $id, UserRole $role): bool
    {
        return $this->userRepository->updateRole($id, $role);
    }

    public function update(int $id, array $data): ?User
    {
        $user = $this->userRepository->find($id);

        if(!$user) {
            return null;
        }

        return $this->userRepository->update($user, $data);
    }

    public function create(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function delete(int $id): bool
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return false;
        }

        return $this->userRepository->delete($user);
    }

    public function restore(int $id): bool
    {
        $user = User::withTrashed()->find($id);

        return $this->userRepository->restore($user);
    }
}
