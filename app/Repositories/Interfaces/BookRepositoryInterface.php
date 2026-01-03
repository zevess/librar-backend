<?php

namespace App\Repositories\Interfaces;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

interface BookRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Book;
    
    public function findByAuthorId(int $authorId): Collection;

    public function create(array $data): Book;

    public function update(Book $book, array $data): Book;

    public function delete(Book $book): bool;

    public function restore(Book $book): bool;

}
