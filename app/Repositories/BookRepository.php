<?php

namespace App\Repositories;

use App\Models\Book;
use App\Repositories\Interfaces\BookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookRepository implements BookRepositoryInterface
{
    public function all(): Collection
    {
        return Book::latest()->get();
    }

    public function find(int $id): ?Book
    {
        return Book::with(['activeReservations', 'author', 'category', 'genres', 'publisher'])->find($id);
    }

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator
    {

        $search = $data['q'] ?? '';
        $genres = $data['genres'];
        $category = $data['category'] ?? '';
        $publishers = $data['publishers'];
        $bookId = $data['bookId'] ?? '';
        $sortColumn = $data['sort'] ?? '';
        $sortOrder = $data['order'] ?? 'desc';
        $allowed = ['created_at', 'title'];
        $column = in_array($sortColumn, $allowed) ? $sortColumn : 'created_at';

        $result = Book::with(['author', 'genres', 'publisher', 'category', 'activeReservations', 'subscribers'])

            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('slug', 'like', "%{$search}%")
                        ->orWhereHas('author', function ($author) use ($search) {
                            $author->where('slug', 'like', "%{$search}%");
                        });
                });
            })
            ->when(!empty($genres), function ($query) use ($genres) {
                $query->whereHas('genres', function ($q) use ($genres) {
                    $q->whereIn('genres.id', $genres);
                });
            })
            ->when(!empty($publishers), function ($query) use ($publishers) {
                $query->whereIn('publisher_id', $publishers);
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category_id', $category);
            })
            ->when($bookId, function ($query) use ($bookId) {
                $query->where('id', $bookId);
            })
            ->orderBy($column, $sortOrder);

        return $result->paginate($perPage)->withQueryString();
    }

    public function findBySlugAndId(string $slug, int $id): ?Book
    {
        return Book::where('slug', $slug)->where('id', $id)->first();
    }

    public function findByAuthor(int $authorId): Collection
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
