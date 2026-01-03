<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class BookRepository implements BookRepositoryInterface
{
    public function all(): Collection
    {
        return Book::latest()->get();
    }

    public function find(int $id): ?Book
    {
        return Book::find($id);
    }

    public function findByAuthorId(int $authorId): Collection
    {
        return Book::where('author_id', $authorId)->get();
    }

    public function create(array $data): Book
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data): Book
    {
        $book->update($data);
        return $book;
    }

    public function delete(Book $book): bool
    {
        return $book->delete();
    }

    public function restore(Book $book): bool
    {
        return $book->restore();
    }
}
