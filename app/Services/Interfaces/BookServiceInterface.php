<?php

namespace App\Services\Interfaces;

use App\Models\Book;
use Illuminate\Database\Eloquent\Collection;

interface BookServiceInterface
{
    public function getAllBooks(): Collection;

    public function getBookById(int $id): ?Book;

    public function createBook(array $data): Book;

    public function updateBook(int $id, array $data): ?Book;

    public function deleteBook(int $id): bool;
}
