<?php

namespace App\Imports;

use App\Models\Publisher;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;

class PublishersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsEmptyRows, SkipsOnError
{

    use SkipsFailures, SkipsErrors;
    /**
     * Create a new class instance.
     */
    private Collection $publishers;
    public function __construct()
    {
        $this->publishers = Publisher::pluck('id', 'slug');
    }

    public function model(array $row)
    {
        return new Publisher([
            'name' => $row['название_издательства'],
            'description' => $row['описание_издательства'],
            'slug' => Str::slug($row['название_издательства'])
        ]);
    }

    public function rules(): array
    {
        return [
            '*.название_издательства' => [
                'required',
                function ($attribute, $value, $fail) {

                    $slug = Str::slug($value);

                    if ($this->publishers->has($slug)) {
                        $fail("Издательство '{$value}' уже существует");
                    }
                }
            ],
            '*.описание_издательства' => [
                'nullable',
                'string'
            ],
        ];
    }
}
