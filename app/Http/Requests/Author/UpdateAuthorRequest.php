<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'UpdateAuthorRequest',
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Имя автора'),
        new OA\Property(property: 'description', type: 'string', example: 'Описание автора'),
    ],
    type: 'object'
)]
class UpdateAuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'slug' => 'nullable',
            'description' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Пожалуйста введите имя автора',
        ];
    }
}
