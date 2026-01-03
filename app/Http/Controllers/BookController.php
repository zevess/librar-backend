<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookCollection;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;

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

    public function store(StoreBookRequest $request): BookResource
    {
        $data = $request->validated();
        $book = $this->bookService->create($data);
        return new BookResource($book);
    }

    public function update(UpdateBookRequest $request, int $id): BookResource|JsonResponse
    {
        $book = $this->bookService->getById($id);

        $book = $this->bookService->update($id, $request->validated());

        if (!$book) {
            return response()->json(["message" => "Не найдено"], 404);
        }
        return new BookResource($book);
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
