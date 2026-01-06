<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;

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
            'description' => 'required|min:10',
            'author_id' => 'nullable'
            // 'years' => 'required|integer'
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'name.required' => 'Пожалуйста введите имя автора',
    //         'description.required' => 'Пожалуйста введите описание',
    //         'years.required' => 'Пожалуйста укажите даты'
    //     ];
    // }
}
