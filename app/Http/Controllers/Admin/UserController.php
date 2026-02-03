<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {
    }

    public function index()
    {
        return $this->userService->getPaginated(25);
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return new UserResource($user);
    }

    public function show(int $id)
    {
        $user = $this->userService->getById($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userService->getById($id);
        $user = $this->userService->update($id, $request->validated());
        return new UserResource($user);
    }
//22|9djAgLUKPidVWKRB0my9nO1vgMDWWNo4GX4VJ4Gc104fbace
    public function updateRole(Request $request, int $id)
    {
        $role = UserRole::from($request->input('role'));
        $user = $this->userService->changeRole($id, $role);
        return response()->json([
            'message' => 'Роль изменена',
            'user' => new UserResource($user)
        ]);
    }

    public function destroy(int $id)
    {
        $this->userService->delete($id);
        return response()->json(["message" => "Пользователь удален"], 200);
    }

    public function restore(int $id)
    {
        $this->userService->restore($id);
        return response()->json([
            "message" => "Пользователь восстановлен"
        ]);
    }
}
