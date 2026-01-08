<?php

namespace App\Services;

use App\Enums\BookStatus;
use App\Exceptions\ApiException;
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

    public function reserve(int $id, int $userId): ?Book
    {
        $book = $this->bookRepository->find($id);
        
        if (!$book) {
            throw new ApiException('Книга не найдена');
        }
        
        if ($book->status == BookStatus::RESERVED || $book->status == BookStatus::ISSUED) {
            throw new ApiException('Книга уже забронирована или выдана');
        }

        $data['status'] = BookStatus::RESERVED->value;
        $data['reserved_by'] = $userId;

        return $this->bookRepository->update($book, $data);

    }

    public function issue(int $id): ?Book
    {
        $book = $this->bookRepository->find($id);
        
        if (!$book) {
            throw new ApiException('Книга не найдена');
        }
        
        if ($book->status == BookStatus::ISSUED) {
            throw new ApiException('Книга уже забронирована или выдана');
        }

        $data['status'] = BookStatus::ISSUED->value;
        
        return $this->bookRepository->update($book, $data);
    }

    public function accept(int $id): ?Book
    {
        $book = $this->bookRepository->find($id);
        
        if (!$book) {
            throw new ApiException('Книга не найдена');
        }
        
        
        $data['status'] = BookStatus::AVAILABLE->value;
        $data['reserved_by'] = null;

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
