<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Author;
use App\Models\Book;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Services\Interfaces\AuthorServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class AuthorService implements AuthorServiceInterface
{

    public function __construct(
        private AuthorRepositoryInterface $authorRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->authorRepository->all();
    }

    public function getById(int $id): ?Author
    {
        $author = $this->authorRepository->find($id);

        if (!$author) {
            throw new ApiException("Автор не найден");
        }

        return $author;
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
}
