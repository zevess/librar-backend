<?php

namespace App\Http\Controllers;

use App\Http\Requests\Author\GetAuthorRequest;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\Author\AuthorCollection;
use App\Http\Resources\Author\AuthorResource;
use App\Http\Resources\Author\AuthorSummaryCollection;
use App\Http\Resources\Author\AuthorSummaryResource;
use App\Imports\AuthorsImport;
use App\Services\Interfaces\AuthorServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use OpenApi\Attributes as OA;

class AuthorController extends Controller
{
    public function __construct(
        private AuthorServiceInterface $authorService,
    ) {}

    #[OA\Get(
        path: '/api/authors',
        operationId: 'getAuthors',
        tags: ['Authors'],
        summary: 'Получение авторов',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/AuthorsResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function index(GetAuthorRequest $request): AuthorCollection
    {
        $authors = $this->authorService->getPaginated($request->validated());
        return new AuthorCollection($authors);
    }

    #[OA\Get(
        path: '/api/admin/authors',
        operationId: 'getAdminAuthors',
        tags: ['Authors'],
        summary: 'Получение авторов для админа',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/PageQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/AuthorsResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function adminPaginated(GetAuthorRequest $request): AuthorCollection
    {
        $authors = $this->authorService->getPaginated($request->validated(), true);
        return new AuthorCollection($authors);
    }

    #[OA\Get(
        path: '/api/authors/{id}',
        operationId: 'getAuthorById',
        tags: ['Authors'],
        summary: 'Получение автора по ID',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/AuthorResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function show(int $id): AuthorResource
    {
        $author = $this->authorService->getById($id);
        return new AuthorResource($author);
    }

    #[OA\Get(
        path: '/api/authors/{slug}-{id}',
        operationId: 'getAuthorBySlugAndId',
        tags: ['Authors'],
        summary: 'Получение автора по Slug и ID',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
            new OA\Parameter(ref: '#/components/parameters/SlugInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/AuthorResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function showBySlugAndId(string $slug, int $id): AuthorResource
    {
        $author = $this->authorService->getBySlugAndId($slug, $id);
        return new AuthorResource($author);
    }

    #[OA\Get(
        path: '/api/authors/query',
        operationId: 'getAuthorsByQuery',
        tags: ['Authors'],
        summary: 'Получение авторов по имени',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/AuthorsSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function getByQuery(Request $request): AuthorSummaryCollection
    {
        $query = $request->input('q');
        $authors = $this->authorService->getByQuery($query);
        return new AuthorSummaryCollection($authors);
    }


    #[OA\Post(
        path: '/api/authors',
        summary: 'Создать автора',
        operationId: 'createAuthor',
        tags: ['Authors'],
        security: [
            ['bearerAuth' => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreAuthorRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/AuthorSummaryResponse'),
        ]
    )]
    public function store(StoreAuthorRequest $request): AuthorSummaryResource
    {
        $author = $this->authorService->create($request->validated());
        return new AuthorSummaryResource($author);
    }


    #[OA\Put(
        path: '/api/authors/{id}',
        summary: 'изменить автора',
        operationId: 'updateAuthor',
        tags: ['Authors'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateAuthorRequest')
        ),
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/AuthorSummaryResponse'),
        ]
    )]
    public function update(UpdateAuthorRequest $request, int $id): AuthorSummaryResource
    {
        $author = $this->authorService->update($id, $request->validated());
        return new AuthorSummaryResource($author);
    }

    #[OA\Delete(
        path: '/api/authors/{id}',
        summary: 'Удалить автора',
        operationId: 'deleteAuthor',
        tags: ['Authors'],
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
        $this->authorService->delete($id);
        return response()->json(["message" => "Удалено"], 200);
    }

    #[OA\Delete(
        path: '/api/authors/{id}/restore',
        summary: 'Восстановить автора',
        operationId: 'restoreAuthor',
        tags: ['Authors'],
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
        $restored = $this->authorService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }
    
    #[OA\Post(
        path: '/api/authors/import',
        summary: 'Импортировать авторов',
        operationId: 'importAuthors',
        tags: ['Authors'],
        security: [
            ['bearerAuth' => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['file'],
                    properties: [
                        new OA\Property(
                            property: 'file',
                            type: 'string',
                            format: 'binary',
                            description: 'Файл для импорта авторов (xlsx)'
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Импорт завершен",
                content: new OA\JsonContent(
                    type: 'object',
                    properties:[
                        new OA\Property(property: 'message', type: 'string'),
                        new OA\Property(property: 'skippedRows', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        $result = $this->authorService->import($request->file('file'));
        return response()->json($result);
    }
}
