<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetUserRequest;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function index(GetUserRequest $request): UserCollection
    {
        $users = $this->userService->getPaginated($request->validated());
        return new UserCollection($users);
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $user = $this->userService->create($request->validated());
        return new UserResource($user);
    }

    public function show(int $id): UserResource
    {
        $user = $this->userService->getById($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $id): UserResource
    {
        $user = $this->userService->update($id, $request->validated());
        return new UserResource($user);
    }

    public function updateRole(Request $request, int $id): JsonResponse|UserResource
    {
        $role = UserRole::from($request->input('role'));
        $user = $this->userService->changeRole($id, $role);
        return response()->json([
            'message' => 'Роль изменена',
            'user' => new UserResource($user)
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->userService->delete($id);
        return response()->json(["message" => "Пользователь удален"], 200);
    }

    public function restore(int $id): JsonResponse
    {
        $this->userService->restore($id);
        return response()->json([
            "message" => "Пользователь восстановлен"
        ]);
    }
}
