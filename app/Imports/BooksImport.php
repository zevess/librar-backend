<?php

namespace App\Imports;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class BooksImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    /**
     * Create a new class instance.
     */
    private Collection $books;
    private Collection $authors;
    private Collection $publishers;
    private Collection $categories;

    public function __construct()
    {
        $this->books = Book::pluck('id', 'slug');
        $this->authors = Author::pluck('id', 'slug');
        $this->publishers = Publisher::pluck('id', 'slug');
        $this->categories = Category::pluck('id', 'slug');
    }

    public function model(array $row)
    {
        $authorSlug = Str::slug($row['автор']);
        $authorId = $this->authors->get($authorSlug);

        $publisherSlug = Str::slug($row['издательство']);
        $publisherId = $this->publishers->get($publisherSlug);

        $categorySlug = Str::slug($row['категория']);
        $categoryId = $this->categories->get($categorySlug);

        $bookSlug = Str::slug($row['название']);
        return new Book([
            'title' => $row['название'],
            'author_id' => $authorId,
            'category_id' => $categoryId,
            'publisher_id' => $publisherId,
            'slug' => $bookSlug,
            'description' => $row['описание']
        ]);
    }

    public function rules(): array
    {
        return [
            '*.название' => [
                'required',
                function ($attribute, $value, $fail) {

                    $slug = Str::slug($value);

                    if ($this->books->has($slug)) {
                        $fail("Книга '{$value}' уже существует");
                    }
                }
            ],
            '*.автор' => [
                'required',
                function ($attribute, $value, $fail) {

                    $slug = Str::slug($value);

                    if (!$this->authors->has($slug)) {
                        $fail("Автор '{$value}' не найден");
                    }
                }
            ],

            '*.категория' => [
                'required',
                function ($attribute, $value, $fail) {

                    $slug = Str::slug($value);

                    if (!$this->categories->has($slug)) {
                        $fail("Категория '{$value}' не найдена");
                    }
                }
            ],
            '*.издательство' => [
                'required',
                function ($attribute, $value, $fail) {

                    $slug = Str::slug($value);

                    if (!$this->publishers->has($slug)) {
                        $fail("Издательство '{$value}' не найдено");
                    }
                }
            ],
            '*.описание' => [
                'nullable',
                'string'
            ],
        ];
    }
}
