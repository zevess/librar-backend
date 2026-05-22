<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreBookRequest',
    required: ['title', 'description', 'publisher_id', 'category_id'],
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Название книги'),
        new OA\Property(property: 'description', type: 'string', example: 'Описание книги'),
        new OA\Property(property: 'image', type: 'string', example: 'Ссылка на изображение'),
        new OA\Property(property: 'author_id', type: 'numeric', example: 'Id автора'),
        new OA\Property(property: 'publisher_id', type: 'numeric', example: 'Id издателя'),
        new OA\Property(property: 'category_id', type: 'numeric', example: 'Id категории'),
    ],
    type: 'object'
)]
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
            'title' => ['required', 'min:1'],
            'slug' => 'nullable',
            'description' => ['required', 'min:10'],
            'image' => ['sometimes', 'string'],
            'author_id' => ['sometimes', 'nullable', 'numeric'],
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
}
