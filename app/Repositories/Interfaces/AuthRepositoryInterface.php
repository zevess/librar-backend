<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;

interface AuthRepositoryInterface
{
    public function register(array $data);

    public function login(array $data);

}
