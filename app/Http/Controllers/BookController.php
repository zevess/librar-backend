<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\GetBookRequest;
use App\Http\Requests\Book\StoreBookRequest;
use App\Http\Requests\Book\UpdateBookRequest;
use App\Http\Resources\Book\BookCollection;
use App\Http\Resources\Book\BookResource;
use App\Http\Resources\Book\BookSummaryCollection;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(
        private BookServiceInterface $bookService
    ) {
    }

    public function index(GetBookRequest $request): BookSummaryCollection
    {
        $books = $this->bookService->getPaginated($request->validated());
        return new BookSummaryCollection($books);
    }

    public function adminPaginated(GetBookRequest $request): BookCollection
    {
        $books = $this->bookService->getPaginated($request->validated(), true);
        return new BookCollection($books);
    }

    public function getByQuery(Request $request): BookSummaryCollection
    {
        $query = $request->input('q');
        $books = $this->bookService->getByQuery($query);
        return new BookSummaryCollection($books);
    }

    public function show(int $id): BookResource
    {
        $book = $this->bookService->getById($id);

        return new BookResource($book);
    }

    public function showBySlugAndId(string $slug, int $id): BookResource
    {
        $book = $this->bookService->getBySlugAndId($slug, $id);
        return new BookResource($book);
    }

    public function store(StoreBookRequest $request): BookResource
    {
        $book = $this->bookService->create($request->validated());
        return new BookResource($book);
    }

    public function update(int $id, UpdateBookRequest $request): BookResource
    {
        $book = $this->bookService->update($id, $request->validated());
        return new BookResource($book);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->bookService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ], 200);
    }

    public function restore(int $id): JsonResponse
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
