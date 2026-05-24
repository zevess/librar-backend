<?php

namespace App\Services;

use App\Models\RefreshToken;
use App\Models\User;
use App\Services\Interfaces\TokenServiceInterface;
use Illuminate\Support\Str;

class TokenService implements TokenServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct( 
    ) {}

    public function createTokens(User $user): array
    {
        $accessToken = $user->createToken(
            name: 'api_token',
            expiresAt: now()->addMinutes(15)
        );
        $rawRefreshToken = Str::random(64);

        RefreshToken::create([
            'user_id'=> $user->id,
            'token' => hash('sha256', $rawRefreshToken),
            'expires_at'=> now()->addDays(30)
        ]);
        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $rawRefreshToken,
        ];
    }

    public function findValidRefreshToken(string $rawToken): ?RefreshToken
    {
        $hashedToken = hash('sha256', $rawToken);
        return RefreshToken::where('token', $hashedToken)->where('expires_at', '>', now())->first();
    }

    public function rotateRefreshToken(RefreshToken $oldToken): string
    {
        $oldToken->delete();
        $rawRefreshToken = Str::random(64);
        RefreshToken::create([
            'user_id' => $oldToken->user_id,
            'token' => hash('sha256', $rawRefreshToken),
            'expires_at'=> now()->addDays(30)
        ]);
        return $rawRefreshToken;
    }


    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
        $user->refreshTokens()->delete();
    }
   
}
