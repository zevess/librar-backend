<?php

namespace App\Http\Requests\Book;

use App\Enums\BookStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class BookStatusRequest extends FormRequest
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
            'status' => ['required', new Enum(BookStatus::class)]
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Укажите новый статус книги'
        ];
    }
}
