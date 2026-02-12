<?php

namespace App\Http\Controllers;

use App\Http\Requests\Book\AttachBookGenresRequest;
use App\Http\Resources\Genre\GenreCollection;
use App\Http\Resources\Genre\GenreResource;
use App\Services\Interfaces\GenreServiceInterface;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function __construct(
        private GenreServiceInterface $genreService
    ) {
    }

    public function index(): GenreCollection
    {
        $genres = $this->genreService->getAll();
        return new GenreCollection($genres);
    }

    public function show($id): GenreResource
    {
        $genre = $this->genreService->getById($id);
        return new GenreResource($genre);

    }

    public function store(string $genreName): GenreResource
    {
        $genre = $this->genreService->create($genreName);
        return new GenreResource($genre);

    }

    public function attach(Request $request, int $bookId)
    {
        $genres = array_map('intval', $request->input('genres') ?: []);

        $this->genreService->attachToBook($bookId, $genres);
        return response()->json([
            "message" => "Жанры присвоены"
        ], 200);
    }

    public function detach(Request $request, int $bookId)
    {
        $genres = array_map('intval', $request->input('genres') ?: []);

        $this->genreService->detachFromBook($bookId, $genres);
        return response()->json([
            "message" => "Жанры удалены"
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->genreService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ], 200);
    }

}
