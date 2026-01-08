<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{

    public function __construct(
        private AuthRepositoryInterface $authRepository
    ) {
    }

    public function register(array $data)
    {
        $user = User::query()
            ->where('email', $data['email'])
            ->first();

        if ($user) {
            throw new ApiException("Пользователь с такой почтой уже существует");
        }

        return $this->authRepository->register($data);
    }

    public function login(array $data)
    {
        $user = $this->authRepository->login($data);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new ApiException("Неверный email или пароль");
        }

        return $user;

    }

}
