<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Exceptions\ApiException;
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
    ) {
    }

    public function getPaginated(?array $data): LengthAwarePaginator
    {
        $perPage = $data['perPage'] ?? 10;
        return $this->userRepository->getPaginated($data, $perPage);
    }

    public function getById(int $id): ?User
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new ApiException("Пользователь не найден");
        }
        return $user;
    }

    public function getByEmail(string $email): ?User
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new ApiException("Пользователь не найден");
        }

        return $user;
    }

    public function changeRole(int $id, UserRole $role): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new ApiException("Пользователь не найден");
        }

        return $this->userRepository->updateRole($id, $role);
    }

    public function update(int $id, array $data)
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new ApiException("Пользователь не найден");
        }

        $isSameUser = auth()->id() === $user->id;
        $isEditorAdmin = auth()->user()->role->value === UserRole::ADMIN->value;
        $isRoleChanging = $data['role'] !== $user->role->value;

        if ($isSameUser && $isEditorAdmin && $isRoleChanging) {
            throw new ApiException('Свою роль изменить нельзя');
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
            throw new ApiException("Пользователь не найден");
        }
        $isSameUser = auth()->id() === $user->id;
        $isEditorAdmin = auth()->user()->role->value === UserRole::ADMIN->value;
        if ($isSameUser && $isEditorAdmin) {
            throw new ApiException('Себя удалить нельзя');
        }

        return $this->userRepository->delete($user);
    }

    public function restore(int $id): bool
    {
        $user = User::withTrashed()->find($id);

        if (!$user) {
            throw new ApiException("Удаленный пользователь не найден");
        }

        return $this->userRepository->restore($user);
    }
}
