<?php

namespace App\Http\Controllers;

use App\Http\Requests\Genre\GetGenreRequest;
use App\Http\Resources\Genre\GenreCollection;
use App\Http\Resources\Genre\GenreResource;
use App\Services\Interfaces\GenreServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class GenreController extends Controller
{
    public function __construct(
        private GenreServiceInterface $genreService
    ) {
    }
    #[OA\Get(
        path: '/api/genres',
        operationId: 'getGenres',
        tags: ['Genres'],
        summary: 'Получение жанров',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/GenresResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function index(Request $request): GenreCollection
    {
        $query = $request->input('q');
        $genres = $this->genreService->getByQuery($query);
        return new GenreCollection($genres);
    }



    #[OA\Get(
        path: '/api/admin/genres',
        operationId: 'getAdminGenres',
        tags: ['Genres'],
        summary: 'Получение жанров для админа',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/GenresResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function adminFiltered(GetGenreRequest $request): GenreCollection
    {
        $genres = $this->genreService->getAdminFiltered($request->validated());
        return new GenreCollection($genres);
    }



    #[OA\Get(
        path: '/api/genres/{id}',
        operationId: 'getGenreById',
        tags: ['Genres'],
        summary: 'Получение жанра',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/GenreResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function show($id): GenreResource
    {
        $genre = $this->genreService->getById($id);
        return new GenreResource($genre);

    }



    #[OA\Post(
        path: '/api/genres',
        summary: 'Создать жанр',
        operationId: 'createGenre',
        tags: ['Genres'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name'],
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/GenreResponse'),
        ]
    )]
    public function store(Request $request): GenreResource
    {
        $genreName = $request->input('name');
        $genre = $this->genreService->create($genreName);
        return new GenreResource($genre);
    }



    #[OA\Put(
        path: '/api/genres',
        summary: 'Изменить жанр',
        operationId: 'updateGenre',
        tags: ['Genres'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name'],
                properties: [
                    new OA\Property(
                        property: 'name',
                        type: 'string',
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/GenreResponse'),
        ]
    )]
    public function update(int $genreId, Request $request): GenreResource
    {
        $data['name'] = $request->input('name');
        $genre = $this->genreService->update($genreId, $data);
        return new GenreResource($genre);
    }

    #[OA\Post(
        path: '/api/genres/attach/{bookId}',
        summary: 'Добавить жанр к книге',
        operationId: 'attachGenre',
        tags: ['Genres'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/BookIdInPath'),
            new OA\Parameter(ref: '#/components/parameters/GenresQuery'),
        ],
        responses: [
            new OA\Response(response: 201, description: 'Жанры присвоены'),
        ]
    )]
    public function attach(Request $request, int $bookId): JsonResponse
    {
        $genres = $request->input('genres');
        $this->genreService->attachToBook($bookId, $genres);
        return response()->json([
            "message" => "Жанры присвоены"
        ], 200);
    }



    #[OA\Delete(
        path: '/api/genres/detach/{bookId}',
        summary: 'Удалить жанр из книге',
        operationId: 'detachGenre',
        tags: ['Genres'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/BookIdInPath'),
            new OA\Parameter(ref: '#/components/parameters/GenresQuery'),
        ],
        responses: [
            new OA\Response(response: 201, description: 'Жанры удалены'),
        ]
    )]
    public function detach(Request $request, int $bookId): JsonResponse
    {
        $genres = $request->input('genres');
        $this->genreService->detachFromBook($bookId, $genres);
        return response()->json([
            "message" => "Жанры удалены"
        ], 200);
    }



    #[OA\Delete(
        path: '/api/genres/{id}',
        summary: 'Удалить жанр',
        operationId: 'deleteGenre',
        tags: ['Genres'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 201, description: "Удалено"),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->genreService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ], 200);
    }



    #[OA\Post(
        path: '/api/genres/{id}/restore',
        summary: 'Восстановить жанр',
        operationId: 'restoreGenre',
        tags: ['Genres'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 201, description: "Восстановлено"),
        ]
    )]
    public function restore(int $id): JsonResponse
    {
        $restored = $this->genreService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }
}
