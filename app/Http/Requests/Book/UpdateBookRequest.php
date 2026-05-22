<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;


#[OA\Schema(
    schema: 'UpdateBookRequest',
    properties: [
        new OA\Property(property: 'title', type: 'string', example: 'Название книги'),
        new OA\Property(property: 'description', type: 'string', example: 'Описание книги'),
        new OA\Property(property: 'image', type: 'string', example: 'Ссылка на изображение'),
        new OA\Property(property: 'remove_image', type: 'boolean'),
        new OA\Property(property: 'author_id', type: 'numeric', example: 'Id автора'),
        new OA\Property(property: 'publisher_id', type: 'numeric', example: 'Id издателя'),
        new OA\Property(property: 'category_id', type: 'numeric', example: 'Id категории'),
    ],
    type: 'object'
)]

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
            'title' => ['sometimes', 'min:3'],
            'slug' => 'nullable',
            'description' => ['sometimes', 'min:10'],
            'image' => ['nullable'],
            'remove_image' => ['sometimes', 'boolean'],
            'author_id' => ['sometimes', 'nullable', 'numeric'],
            'publisher_id' => ['sometimes', 'numeric'],
            'category_id' => ['sometimes', 'numeric']
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'title.required' => 'Пожалуйста введите название книги',
    //         'description.required' => 'Пожалуйста введите описание'
    //     ];
    // }

    // protected function prepareForValidation(): void
    // {
    //     $this->merge([
    //         'is_published' => $this->boolean('is_published'),
    //         'remove_image' => $this->boolean('remove_image')
    //     ]);
    // }
}
