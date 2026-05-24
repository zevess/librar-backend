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
use OpenApi\Attributes as OA;

class BookController extends Controller
{
    public function __construct(
        private BookServiceInterface $bookService
    ) {
    }

    #[OA\Get(
        path: '/api/books',
        operationId: 'getBooks',
        tags: ['Books'],
        summary: 'Получение книг',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/CategoryQuery'),
            new OA\Parameter(ref: '#/components/parameters/GenresQuery'),
            new OA\Parameter(ref: '#/components/parameters/PublishersQuery'),
            new OA\Parameter(ref: '#/components/parameters/PageQuery'),
            new OA\Parameter(ref: '#/components/parameters/StatusQuery'),
            new OA\Parameter(ref: '#/components/parameters/SortQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
            new OA\Parameter(ref: '#/components/parameters/OrderQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/BooksSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function index(GetBookRequest $request): BookSummaryCollection
    {
        $books = $this->bookService->getPaginated($request->validated());
        return new BookSummaryCollection($books);
    }

    #[OA\Get(
        path: '/api/admin/books',
        operationId: 'getAdminBooks',
        tags: ['Books'],
        summary: 'Получение книг для админа',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/CategoryQuery'),
            new OA\Parameter(ref: '#/components/parameters/GenresQuery'),
            new OA\Parameter(ref: '#/components/parameters/PublishersQuery'),
            new OA\Parameter(ref: '#/components/parameters/PageQuery'),
            new OA\Parameter(ref: '#/components/parameters/StatusQuery'),
            new OA\Parameter(ref: '#/components/parameters/SortQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
            new OA\Parameter(ref: '#/components/parameters/OrderQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/BooksSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function adminPaginated(GetBookRequest $request): BookCollection
    {
        $books = $this->bookService->getPaginated($request->validated(), true);
        return new BookCollection($books);
    }


    #[OA\Get(
        path: '/api/books/query',
        operationId: 'getBooksByQuery',
        tags: ['Books'],
        summary: 'Получение книг по названию',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/BooksSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function getByQuery(Request $request): BookSummaryCollection
    {
        $query = $request->input('q');
        $books = $this->bookService->getByQuery($query);
        return new BookSummaryCollection($books);
    }

    #[OA\Get(
        path: '/api/books/{id}',
        operationId: 'getBookById',
        tags: ['Books'],
        summary: 'Получение книги по ID',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/BookResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function show(int $id): BookResource
    {
        $book = $this->bookService->getById($id);
        return new BookResource($book);
    }

    #[OA\Get(
        path: '/api/books/{slug}-{id}',
        operationId: 'getBookBySlugAndId',
        tags: ['Books'],
        summary: 'Получение книги по Slug и ID',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SlugInPath'),
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/BookResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function showBySlugAndId(string $slug, int $id): BookResource
    {
        $book = $this->bookService->getBySlugAndId($slug, $id);
        return new BookResource($book);
    }

    #[OA\Post(
        path: '/api/books',
        summary: 'Создать книгу',
        operationId: 'createBook',
        tags: ['Books'],
        security: [
            ['bearerAuth' => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreBookRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/BookResponse'),
        ]
    )]
    public function store(StoreBookRequest $request): BookResource
    {
        $book = $this->bookService->create($request->validated());
        return new BookResource($book);
    }


    #[OA\Put(
        path: '/api/books/{id}',
        summary: 'Изменить книгу',
        operationId: 'updateBook',
        tags: ['Books'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateBookRequest')
        ),
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/BookResponse'),
        ]
    )]
    public function update(int $id, UpdateBookRequest $request): BookResource
    {
        $book = $this->bookService->update($id, $request->validated());
        return new BookResource($book);
    }

    #[OA\Delete(
        path: '/api/books/{id}',
        summary: 'Удалить книгу',
        operationId: 'deleteBook',
        tags: ['Books'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, description: "Удалено"),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->bookService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ], 200);
    }

    #[OA\Post(
        path: '/api/books/{id}/restore',
        summary: 'Восстановить книгу',
        operationId: 'restoreBook',
        tags: ['Books'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, description: "Восстановлено"),
        ]
    )]
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
