<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RegisterRequest',
    required: ['name', 'email', 'password', 'password_confirmation'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Имя пользователя'),
        new OA\Property(property: 'email', type: 'string', example: 'email пользователя'),
        new OA\Property(property: 'password', type: 'string', example: 'Пароль'),
        new OA\Property(property: 'password_confirmation', type: 'string', example: 'Подтвердите пароль'),
    ],
    type: 'object'
)]

class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

}
