<?php

namespace App\Http\Controllers;

use App\Enums\BookStatus;
use App\Http\Requests\Book\BookStatusRequest;
use App\Http\Requests\Book\GetBookRequest;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\PaginatedCollection;
use App\Models\Book;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct(
        private BookServiceInterface $bookService
    ) {
    }

    public function index(GetBookRequest $request): BookCollection
    {
        $books = $this->bookService->getPaginated($request->validated(), 2);
        return new BookCollection($books);
    }

    public function show(int $id): BookResource
    {
        $book = $this->bookService->getById($id);
  
        return new BookResource($book);
    }

    public function store(StoreBookRequest $request): BookResource
    {
        $book = $this->bookService->create($request->validated());
        $book->load('author');
        $book->load('genres');
        $book->load('publisher');
        $book->load('category');
        return new BookResource($book);
    }

    public function update(int $id, UpdateBookRequest $request): BookResource|JsonResponse
    {
        $book = $this->bookService->update($id, $request->validated());
        return new BookResource($book);
    }

    public function destroy(int $id): BookResource|JsonResponse
    {

        $this->bookService->delete($id);

        return response()->json([
            "message" => "Удалено"
        ], 200);
    }

    public function restore(int $id): BookResource|JsonResponse
    {
        $restored = $this->bookService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }
}
