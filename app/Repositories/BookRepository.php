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

    public function findByAuthor(int $authorId): Collection
    {
        return Book::where('author_id', $authorId)->get();
    }

    public function findBySlugAndId(string $slug, int $id): ?Book
    {
        return Book::with(['author', 'category', 'genres', 'publisher', 'activeReservations', 'subscribers'])->where('slug', $slug)->where('id', $id)->first();
    }

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $search = $data['q'] ?? '';
        $genres = $data['genres'];
        $category = $data['category'] ?? '';
        $publishers = $data['publishers'];
        $id = $data['id'] ?? '';
        $sortColumn = $data['sort'] ?? '';
        $sortOrder = $data['order'] ?? 'desc';
        $allowed = ['created_at', 'title'];
        $status = $data['status'] ?? '';
        $column = in_array($sortColumn, $allowed) ? $sortColumn : 'created_at';

        $result = Book::with(['author', 'genres', 'publisher', 'category', 'activeReservations', 'subscribers', 'reservation'])

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
            ->when($id, function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->when($status === 'reserved', fn($q) => $q->whereHas(
                'reservation',
                fn($q) =>
                $q->whereIn('status', ['reserved', 'issued'])
            ))
            ->when($status === 'available', fn($q) => $q->whereDoesntHave(
                'reservation',
                fn($q) =>
                $q->whereIn('status', ['reserved', 'issued'])
            ))
            ->withTrashed($includeTrashed)
            ->orderBy($column, $sortOrder);

        return $result->paginate($perPage)->withQueryString();
    }

    public function getBySlug(?string $slug): Collection
    {
        return Book::query()->when($slug !== '', function ($query) use ($slug) {
            $query->where(function ($q) use ($slug) {
                $q->where('slug', 'like', "%{$slug}%")
                    ->orWhereHas('author', function ($author) use ($slug) {
                        $author->where('slug', 'like', "%{$slug}%");
                    });
            });
        })->take(5)->get();
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
