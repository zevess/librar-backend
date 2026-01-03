<?php

namespace App\Services;

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
    ){}

    public function getAll(): Collection
    {
        return $this->authorRepository->all();
    }

    public function getById(int $id): ?Author
    {
        return $this->authorRepository->find($id);
    }

    public function create(array $data): Author
    {
        $slug = Str::slug($data["name"]);
        $data['slug'] = $slug;
        return $this->authorRepository->create($data);
    }

    public function update(int $id, array $data): ?Author
    {
        $author = $this->authorRepository->find($id);
        if(!$author) {
            return null;
        }

        $slug = Str::slug($data["name"]);
        $data['slug'] = $slug;

        return $this->authorRepository->update($author, $data);
    }

    public function delete(int $id): bool
    {
        $author = $this->authorRepository->find($id);
        if(!$author) {
            return false;
        }

        return $this->authorRepository->delete($author);
    }
}
