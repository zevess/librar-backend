<?php

namespace App\Http\Controllers;

use App\Http\Requests\Genre\GetGenreRequest;
use App\Http\Resources\Genre\GenreCollection;
use App\Http\Resources\Genre\GenreResource;
use App\Services\Interfaces\GenreServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function __construct(
        private GenreServiceInterface $genreService
    ) {
    }

    public function index(Request $request): GenreCollection
    {
        $query = $request->input('q');
        $genres = $this->genreService->getByQuery($query);
        return new GenreCollection($genres);
    }

    public function adminPaginated(GetGenreRequest $request): GenreCollection
    {
        $genres = $this->genreService->getPaginated($request->validated(), true);
        return new GenreCollection($genres);
    }

    public function adminFiltered(GetGenreRequest $request): GenreCollection
    {
        $genres = $this->genreService->getAdminFiltered($request->validated());
        return new GenreCollection($genres);
    }

    public function show($id): GenreResource
    {
        $genre = $this->genreService->getById($id);
        return new GenreResource($genre);

    }

    public function store(Request $request): GenreResource
    {
        $genreName = $request->input('name');
        $genre = $this->genreService->create($genreName);
        return new GenreResource($genre);

    }

    public function update(int $genreId, Request $request): GenreResource
    {
        $data['name'] = $request->input('name');
        $genre = $this->genreService->update($genreId, $data);
        return new GenreResource($genre);
    }

    public function attach(Request $request, int $bookId): JsonResponse
    {
        $genres = $request->input('genres');
        $this->genreService->attachToBook($bookId, $genres);
        return response()->json([
            "message" => "Жанры присвоены"
        ], 200);
    }

    public function detach(Request $request, int $bookId): JsonResponse
    {
        $genres = $request->input('genres');
        $this->genreService->detachFromBook($bookId, $genres);
        return response()->json([
            "message" => "Жанры удалены"
        ], 200);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->genreService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ], 200);
    }
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
