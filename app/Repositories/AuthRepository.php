<?php

namespace App\Repositories;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

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
