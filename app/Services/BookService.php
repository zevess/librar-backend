<?php

namespace App\Services;

use App\Enums\BookStatus;
use App\Exceptions\ApiException;
use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function getPaginated(?string $search, int $perPage): LengthAwarePaginator
    {
        $slug = Str::slug($search);

        return $this->bookRepository->getPaginated($slug, $perPage);
    }

    public function getById(int $id): ?Book
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            throw new ApiException("Книга не найдена");
        }

        return $book;
    }

    public function getByAuthorId(int $authorId): Collection
    {
        $books = $this->bookRepository->findByAuthorId($authorId);

        if (!$books) {
            throw new ApiException("Книга не найдена");
        }

        return $books;
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
            throw new ApiException("Книга не найдена");
        }
        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        return $this->bookRepository->update($book, $data);
    }

    public function delete(int $id): bool
    {
        $book = $this->bookRepository->find($id);
        if (!$book) {
            throw new ApiException("Книга не найдена");
        }

        return $this->bookRepository->delete($book);
    }

    public function restore(int $id): bool
    {
        $book = Book::withTrashed()->find($id);
        if (!$book) {
            throw new ApiException("Удаленная книга не найдена");
        }

        return $this->bookRepository->restore($book);
    }
}
