<?php

namespace App\Imports;

use App\Models\Author;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;

class AuthorsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, SkipsOnError
{

    use SkipsFailures, SkipsErrors;
    /**
     * Create a new class instance.
     */
    private Collection $authors;
    public function __construct()
    {
        $this->authors = Author::pluck('id', 'slug');
    }

    public function model(array $row)
    {
        return new Author([
            'name' => $row['имя_автора'],
            'description' => $row['описание_автора'],
            'slug' => Str::slug($row['имя_автора'])
        ]);
    }

    public function rules(): array
    {
        return [
            '*.имя_автора' => [
                'required',
                function ($attribute, $value, $fail) {

                    $slug = Str::slug($value);

                    if ($this->authors->has($slug)) {
                        $fail("Автор '{$value}' уже существует");
                    }
                }
            ],
            '*.описание_автора' => [
                'nullable',
                'string'
            ],
        ];
    }
}
