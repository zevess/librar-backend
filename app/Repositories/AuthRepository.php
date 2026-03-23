<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        return User::query()->create($data);
    }

    public function login(array $data)
    {
        return User::query()
            ->where('email', $data['email'])
            ->first();
    }

}
