<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
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
        return $this->userService->create($request->validated());
    }

    public function show(int $id)
    {
        $user = $this->userService->getById($id);

        if (!$user) {
            return response()->json(["message" => "not found"], 404);
        }

        return $user;
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return response()->json(["message" => "not found"], 404);
        }

        $user = $this->userService->update($id, $request->validated());

        return $user;
    }

    public function updateRole(Request $request, int $id)
    {
        $user = $this->userService->getById($id);
        if (!$user) {
            return response()->json(["message" => "not found"], 404);
        }

        $role = UserRole::from($request->input('role'));

        $this->userService->changeRole($id, $role);

        return response()->json(['message'=> 'Role updated'],200);
    }

    public function destroy(int $id)
    {
        $deleted = $this->userService->delete($id);

        if(!$deleted) {
            return response()->json(["message"=> "not found"],404);
        }

        return response()->json(["message"=> "Удалено"],200);
    }

    public function restore(int $id)
    {
        $restored = $this->userService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }
}
