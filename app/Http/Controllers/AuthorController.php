<?php

namespace App\Http\Controllers;

use App\Http\Requests\Author\GetAuthorRequest;
use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\Author\AuthorCollection;
use App\Http\Resources\Author\AuthorResource;
use App\Http\Resources\Author\AuthorSummaryCollection;
use App\Http\Resources\Author\AuthorSummaryResource;
use App\Services\Interfaces\AuthorServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        private AuthorServiceInterface $authorService,
    ) {
    }

    public function index(GetAuthorRequest $request): AuthorCollection
    {
        $authors = $this->authorService->getPaginated($request->validated());
        return new AuthorCollection($authors);
    }

    public function adminPaginated(GetAuthorRequest $request): AuthorCollection
    {
        $authors = $this->authorService->getPaginated($request->validated(), true);
        return new AuthorCollection($authors);
    }

    public function store(StoreAuthorRequest $request): AuthorSummaryResource
    {
        $author = $this->authorService->create($request->validated());
        return new AuthorSummaryResource($author);
    }

    public function show(int $id): AuthorResource
    {
        $author = $this->authorService->getById($id);
        return new AuthorResource($author);
    }

    public function showBySlugAndId(string $slug, int $id): AuthorResource
    {
        $author = $this->authorService->getBySlugAndId($slug, $id);
        return new AuthorResource($author);
    }

    public function getByQuery(Request $request): AuthorSummaryCollection
    {
        $query = $request->input('q');
        $authors = $this->authorService->getByQuery($query);
        return new AuthorSummaryCollection($authors);
    }

    public function update(UpdateAuthorRequest $request, int $id): AuthorSummaryResource
    {
        $author = $this->authorService->update($id, $request->validated());
        return new AuthorSummaryResource($author);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->authorService->delete($id);
        return response()->json(["message" => "Удалено"], 200);
    }

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
}
