<?php

namespace App\Services;

use App\Enums\BookStatus;
use App\Exceptions\ApiException;
use App\Models\Book;
use App\Repositories\Interfaces\AuthorRepositoryInterface;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\PublisherRepositoryInterface;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class BookService implements BookServiceInterface
{

    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private PublisherRepositoryInterface $publisherRepository,
        private AuthorRepositoryInterface $authorRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->bookRepository->all();
    }

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator
    {
        $data['q'] = Str::slug($data['q'] ?? '');
        $data['genres'] = array_map('intval', $data['genres'] ?? []);

        return $this->bookRepository->getPaginated($data, $perPage);
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
        $books = $this->bookRepository->findByAuthor($authorId);

        if (!$books) {
            throw new ApiException("Книга не найдена");
        }

        return $books;
    }

    public function create(array $data): Book
    {
        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;

        $existingCategory = $this->categoryRepository->find($data['category_id']);
        if (!$existingCategory) {
            throw new ApiException('Категория не найдена');
        }
        
        $existingAuthor = $this->authorRepository->find($data['author_id']);
        if(!$existingAuthor){
            throw new ApiException('Автор не найден');
        }

        $existingPublisher = $this->publisherRepository->find($data['publisher_id']);
        if (!$existingPublisher) {
            throw new ApiException('Издатель не найден');
        }

        return $this->bookRepository->create($data);
    }

    public function update(int $id, array $data): ?Book
    {
        $book = $this->bookRepository->find($id);
        if (!$book) {
            throw new ApiException("Книга не найдена");
        }

        $existingCategory = $this->categoryRepository->find($data['category_id']);
        if (!$existingCategory) {
            throw new ApiException('Категория не найдена');
        }

        $existingPublisher = $this->publisherRepository->find($data['publisher_id']);
        if (!$existingPublisher) {
            throw new ApiException('Издатель не найден');
        }
        

        $slug = Str::slug($data['title'] ?? $book->title);
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
