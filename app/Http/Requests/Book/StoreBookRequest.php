<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title' => ['required', 'min:3'],
            'slug' => 'nullable',
            'description' => ['required', 'min:10'],
            'image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'author_id' => ['required', 'numeric'],
            'publisher_id' => ['required', 'numeric'],
            'category_id' => ['required', 'numeric']
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Пожалуйста укажите название книги',
            'description.required' => 'Пожалуйста укажите описание',
            'author_id.required' => 'Пожайлуста укажите id автора',
            'publisher_id.required' => 'Пожайлуста укажите id издательства',
            'category_id.required' => 'Пожайлуста укажите id категории',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
        ]);
    }
}
