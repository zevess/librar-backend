<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'StoreReviewRequest',
    required: ['rating'],
    properties: [
        new OA\Property(property: 'text', type: 'string', example: 'Текст отзыва'),
        new OA\Property(property: 'rating', type: 'int', example: 'Оценка'),
    ],
    type: 'object'
)]

class StoreReviewRequest extends FormRequest
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
            // 'book_id' => ['required', 'numeric'],
            'text' => ['sometimes', 'string', 'max:1024'],
            'rating' => ['required', 'numeric', 'min:1', 'max:5']
        ];
    }

    public function messages()
    {
        return [
            // 'book_id.required' => 'Пожалуйста укажите id книги',
            'rating.required' => 'Пожалуйста укажите оценку'
        ];
    }
}
