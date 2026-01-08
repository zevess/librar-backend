<?php

namespace App\Http\Controllers;

use App\Enums\BookStatus;
use App\Http\Requests\Book\BookStatusRequest;
use App\Http\Requests\Book\StoreBookRequest as BookStoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest as BookUpdateBookRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    public function __construct(
        private BookServiceInterface $bookService
    ) {
    }

    public function index(): BookCollection
    {
        $books = $this->bookService->getAll();
        return new BookCollection($books);
    }

    public function show(int $id): BookResource|JsonResponse
    {
        $book = $this->bookService->getById($id);
        if (!$book) {
            return response()->json(["message" => "not found"], 404);
        }

        $book->load('author');

        return new BookResource($book);
    }

    public function store(BookStoreBookRequest $request): BookResource
    {
        $data = $request->validated();
        $book = $this->bookService->create($data);
        return new BookResource($book);
    }

    public function update(BookUpdateBookRequest $request, int $id): BookResource|JsonResponse
    {
        $book = $this->bookService->getById($id);

        if (!$book) {
            return response()->json(["message" => "Не найдено"], 404);
        }

        $book = $this->bookService->update($id, $request->validated());

        return new BookResource($book);
    }

    public function reserve(int $id)
    {
        $book = $this->bookService->reserve($id, auth()->id());
        
        return response()->json([
            'message' => "Книга забронирована",
            'book' => new BookResource($book),
        ]);
    }

    public function issue(int $id)
    {
        $book = $this->bookService->issue($id);
        
        return response()->json([
            'message' => "Книга выдана",
            'book' => new BookResource($book),
        ]);
    }

    public function accept(int $id)
    {
        $book = $this->bookService->accept($id);
        
        return response()->json([
            'message' => "Книга принята",
            'book' => new BookResource($book),
        ]);
    }

    public function destroy(int $id): BookResource|JsonResponse
    {
        $book = $this->bookService->getById($id);

        $deleted = $this->bookService->delete($id);

        if (!$deleted) {
            return response()->json(["message" => "Не найдено"], 404);
        }

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
