<?php

namespace App\Services\Interfaces;

use App\Models\RefreshToken;
use App\Models\User;

interface TokenServiceInterface
{
    public function createTokens(User $user): array;

    public function findValidRefreshToken(string $rawToken): ?RefreshToken;

    public function rotateRefreshToken(RefreshToken $oldToken): string;

    public function revokeAllTokens(User $user): void;
}
