<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'required|min:3',
            'slug' => 'nullable',
            'description' => 'required|min:10',
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'remove_image' => ['sometimes', 'boolean']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Пожалуйста введите название книги',
            'description.required' => 'Пожалуйста введите описание'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'remove_image' => $this->boolean('remove_image')
        ]);
    }
}
