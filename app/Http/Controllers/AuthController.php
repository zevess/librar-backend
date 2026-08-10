<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\Interfaces\AuthServiceInterface;
use App\Services\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{

    public function __construct(
        private AuthServiceInterface $authService,
        private TokenService $tokenService
    ) {
    }


    #[OA\Post(
        path: '/api/auth/register',
        summary: 'Зарегистрироваться',
        operationId: 'register',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/RegisterResponse'),
        ]
    )]
    public function register(RegisterRequest $request): JsonResponse
    {

        $user = $this->authService->register($request->validated());
        $tokens = $this->tokenService->createTokens($user);

        return response()->json([
            'user' => $user,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token']
        ]);
    }


    #[OA\Post(
        path: '/api/auth/login',
        summary: 'Войти',
        operationId: 'login',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/LoginResponse'),
        ]
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());
        $tokens = $this->tokenService->createTokens($user);

        return response()->json([
            'user' => $user,
            'access_token' => $tokens['access_token'],
            'refresh_token' => $tokens['refresh_token']
        ]);
    }

    #[OA\Post(
        path: '/api/auth/refresh',
        summary: 'Обновить refresh токен',
        operationId: 'refresh',
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['refreshToken'],
                properties: [
                    new OA\Property(
                        property: 'refreshToken',
                        type: 'string',
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Обновление токена',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'accessToken',
                            type: 'string',
                            description: 'accessToken',
                        ),
                        new OA\Property(
                            property: 'refreshToken',
                            type: 'string',
                            description: 'refreshToken',
                        )
                    ]
                )
            ),

        ]
    )]
    public function refresh(Request $request): JsonResponse
    {
        $request->validate([
            'refreshToken' => 'required|string'
        ]);

        $refreshToken = $this->tokenService->findValidRefreshToken($request->refreshToken);

        if (!$refreshToken) {
            return response()->json([
                'message' => 'Недействительный refresh токен'
            ], 404);
        }

        $newRefreshToken = $this->tokenService->rotateRefreshToken($refreshToken);
        $user = $refreshToken->user;
        $accessToken = $user->createToken(
            name: 'api_token',
            expiresAt: now()->addMinutes(15)
        );

        return response()->json([
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $newRefreshToken,
        ]);
    }


    #[OA\Get(
        path: '/api/auth/me',
        summary: 'Получение профиля',
        operationId: 'me',
        tags: ['Auth'],
        security: [
            ['bearerAuth' => []]
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/UserResponse'),
        ]
    )]
    public function me(Request $request): JsonResponse|UserResource
    {
        return new UserResource($request->user());
    }

    public function sendVerification(Request $request): JsonResponse
    {
        $request->user()->sendEmailVerificationNotification();
        return response()->json([
            'success' => true,
            'message' => "Письмо с подтверждением почты успешно отправлено"
        ], 201);
    }

    public function verifyEmail(Request $request, $id, $hash): JsonResponse
    {
        $user = User::findOrFail($id);
        if (!hash_equals($hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'success' => false,
                'message' => "Неверная ссылка подтверждения"
            ], 400);
        }

        if (!$request->hasValidSignature()) {
            return response()->json([
                'success' => false,
                'message' => "Устаревшая или невалидная ссылка"
            ], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => "Аккаунт уже подтвержден"
            ], 400);
        }
        $user->markEmailAsVerified();
        return response()->json([
            'success' => true,
            'message' => "Аккаунт подтвержден"
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $email = $request->validate(['email' => 'required|email']);

        $isUserExists = User::query()->where('email', $email)->withTrashed()->first();
        if (!$isUserExists) {
            throw new ApiException("Пользователь с такой почтой не существует");
        }

        $status = Password::sendResetLink($email);

        if ($status === Password::ResetLinkSent) {
            return response()->json([
                'success' => true,
                'message' => "Письмо со сбросом пароля успешно отправлено"
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => "Ошибка при отправке отправке токена",
            'status' => trans($status)
        ], 400);

    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = Password::reset(
            $request->validated(),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        if ($status === Password::PasswordReset) {
            return response()->json([
                'success' => true,
                'message' => 'Пароль успешно изменён'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Неверный токен или email',
            'status' => trans($status)
        ], 400);
    }

    #[OA\Post(
        path: '/api/auth/logout',
        summary: 'Выйти',
        operationId: 'logout',
        tags: ['Auth'],
        security: [
            ['bearerAuth' => []]
        ],
        responses: [
            new OA\Response(response: 201, description: "Вы успешно вышли"),
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $this->tokenService->revokeAllTokens($user);
        return response()->json([
            'message' => 'Вы успешно вышли'
        ]);
    }
}
