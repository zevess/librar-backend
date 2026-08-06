<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Imports\AuthorsImport;
use App\Models\Author;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Services\Interfaces\AuthorServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AuthorService implements AuthorServiceInterface
{

    public function __construct(
        private AuthorRepositoryInterface $authorRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->authorRepository->all();
    }

    public function getPaginated(?array $data, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $data['q'] = Str::slug($data['q'] ?? '');
        $perPage = $data['perPage'] ?? 10;
        return $this->authorRepository->getPaginated($data, $perPage, $includeTrashed);
    }

    public function getByQuery(?string $query): Collection
    {
        $slug = Str::slug($query);
        $authors = $this->authorRepository->getBySlug($slug);
        return $authors;
    }

    public function getById(int $id): ?Author
    {
        $author = $this->authorRepository->find($id);

        if (!$author) {
            throw new ApiException("Автор не найден");
        }

        return $author;
    }

    public function getBySlugAndId(string $slug, int $id): ?Author
    {
        $author = $this->authorRepository->findBySlugAndId($slug, $id);

        if (!$author) {
            throw new ApiException("Автор не найден");
        }

        return $author;
    }

    public function getIdBySlug(?string $query)
    {
        $slug = Str::slug($query);
        $authorId = Author::select('id', 'slug')->firstWhere('slug', 'like', "%{$slug}%")->id;
        return $authorId;
    }

    public function getBySelectedField(?array $fields): Collection
    {
        return $this->authorRepository->getBySelectedField($fields);
    }

    public function create(array $data): Author
    {
        $slug = Str::slug($data["name"]);
        $data['slug'] = $slug;
        $author = $this->authorRepository->create($data);
        return $author;
    }

    public function update(int $id, array $data): ?Author
    {
        $author = $this->authorRepository->find($id);
        if (!$author) {
            throw new ApiException("Автор не найден");
        }

        $slug = Str::slug($data["name"]);
        $data['slug'] = $slug;

        return $this->authorRepository->update($author, $data);
    }

    public function delete(int $id): bool
    {
        $author = $this->authorRepository->find($id);
        if (!$author) {
            throw new ApiException("Автор не найден");
        }

        return $this->authorRepository->delete($author);
    }

    public function restore(int $id): bool
    {
        $author = Author::withTrashed()->find($id);
        if (!$author) {
            throw new ApiException("Удаленный автор не найден");
        }
        return $this->authorRepository->restore($author);
    }

    public function import(UploadedFile $file): array
    {
        $import = new AuthorsImport();
        Excel::import($import, $file);
        $skippedRows = [];
        if ($import->failures()->isNotEmpty()) {
            foreach ($import->failures() as $failure) {
                $skippedRows[] = "Строка " . $failure->row() . ": " . implode(', ', $failure->errors());
            }
        }

        if ($import->errors()->isNotEmpty()) {
            foreach ($import->errors() as $error) {
                $skippedRows[] = "Ошибка: " . $error->getMessage();
            }
        }

        return [
            'message' => 'Импорт завершен',
            'skippedRows' => empty($skippedRows) ? "Все данные успешно импортированы" : $skippedRows
        ];
    }
}
