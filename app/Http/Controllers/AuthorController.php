<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorCollection;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\BookCollection;
use App\Services\BookService;
use App\Services\Interfaces\AuthorServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function __construct(
        private AuthorServiceInterface $authorService,
        private BookService $bookService
    ){}

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
        if(!$author) {
            return response()->json(["message"=> "not found"],404);
        }

        return new AuthorResource($author);
    }

    public function showWithBooks(int $id){
        $author = $this->authorService->getById($id);
        $books = $this->bookService->getByAuthorId($id);
        
        if(!$author) {
            return response()->json(["message"=> "not found"],404);
        }

        return response()->json([
            "author" => new AuthorResource($author),
            "books" => new BookCollection($books)
        ]);
    }

    public function update(UpdateAuthorRequest $request, int $id): AuthorResource|JsonResponse
    {
        
        $author = $this->authorService->getById($id);
        $author = $this->authorService->update($id, $request->validated());

        if(!$author) {
            return response()->json(["message"=> "not found"],404);
        }

        return new AuthorResource($author);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->authorService->delete($id);
        if(!$deleted) {
            return response()->json(["message"=> "not found"],404);
        }

        return response()->json(["message"=> "Удалено"],200);

    }
}
