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
        return Book::find($id);
    }

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator
    {

        $search = $data['q'] ?? '';
        $genres = $data['genres'];
        $category = $data['category'];
        $publisher = $data['publisher'] ?? '';

        $result = Book::with(['author', 'genres', 'publisher', 'category'])

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
            ->when($publisher, function ($query) use ($publisher){
                $query->where('publisher_id', $publisher);
            })
            ->when($category, function ($query) use ($category){
                $query->where('category_id', $category);
            });
        
        return $result->paginate($perPage)->withQueryString();
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
