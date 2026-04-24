<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    public function register(array $data);

    public function login(array $data);

    public function forgotPassword(string $email);

}
