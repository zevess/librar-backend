<?php

namespace App\Http\Controllers;

use App\Http\Requests\Author\StoreAuthorRequest;
use App\Http\Requests\Author\UpdateAuthorRequest;
use App\Http\Resources\Author\AuthorCollection;
use App\Http\Resources\Author\AuthorResource;
use App\Services\Interfaces\AuthorServiceInterface;
use App\Services\Interfaces\BookServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        private AuthorServiceInterface $authorService,
    ) {
    }

    public function index(): AuthorCollection
    {
        $authors = $this->authorService->getAll();

        return new AuthorCollection($authors);
    }

    public function store(StoreAuthorRequest $request): AuthorResource
    {
        $data = $request->validated();

        $author = $this->authorService->create($data);
        return new AuthorResource($author);
    }

    public function show(int $id): AuthorResource|JsonResponse
    {
        $author = $this->authorService->getById($id);
        return new AuthorResource($author);
    }

    public function update(UpdateAuthorRequest $request, int $id): AuthorResource|JsonResponse
    {

        $author = $this->authorService->update($id, $request->validated());
        return new AuthorResource($author);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->authorService->delete($id);

        return response()->json(["message" => "Удалено"], 200);

    }
}
