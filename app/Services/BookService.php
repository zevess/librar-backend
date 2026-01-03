<?php

namespace App\Services;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BookService implements BookServiceInterface
{

    public function __construct(
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->bookRepository->all();
    }

    public function getById(int $id): ?Book
    {
        return $this->bookRepository->find($id);
    }

    public function getByAuthorId(int $authorId): Collection
    {
        return $this->bookRepository->findByAuthorId($authorId);
    }

    public function create(array $data): Book
    {
        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;

        return $this->bookRepository->create($data);
    }

    public function update(int $id, array $data): ?Book
    {
        $book = $this->bookRepository->find($id);
        if (!$book) {
            return null;
        }
        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        return $this->bookRepository->update($book, $data);
    }

    public function delete(int $id): bool
    {
        $book = $this->bookRepository->find($id);
        if (!$book) {
            return false;
        }

        return $this->bookRepository->delete($book);
    }

    public function restore(int $id): bool
    {
        $book = Book::withTrashed()->find($id);

        return $this->bookRepository->restore($book);

    }
}
