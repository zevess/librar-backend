<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Genre;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\GenreRepositoryInterface;
use App\Services\Interfaces\GenreServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class GenreService implements GenreServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private GenreRepositoryInterface $genreRepository,
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->genreRepository->all();
    }

    public function getById(int $id): Genre
    {
        $genre = $this->genreRepository->find($id);
        if (!$genre) {
            throw new ApiException("Жанр не найден");
        }
        return $genre;
    }

    public function create(string $genreName): Genre
    {
        $slug = Str::slug($genreName);
        $data['name'] = $genreName;
        $data['slug'] = $slug;

        $existingGenre = $this->genreRepository->findBySlug($slug);
        if ($existingGenre) {
            throw new ApiException("Жанр уже существует");
        }

        return $this->genreRepository->create($data);
    }

    public function update(int $id, array $data): Genre
    {
        $genre = $this->genreRepository->find($id);
        if (!$genre) {
            throw new ApiException("Жанр не найден");
        }
        $slug = Str::slug($data['name']);
        $data['slug'] = $slug;
        return $this->genreRepository->update($genre, $data);
    }

    public function attachToBook(int $bookId, array $genres): bool
    {
        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            throw new ApiException("Книга не найдена");
        }

        $exists = Genre::whereIn('id', $genres)->pluck('id')->all();

        if (count($exists) !== count($genres)) {
            throw new ApiException("Один или более жанров не существуют. Пожалуйста, укажите корректные жанры");
        }

        $attachedGenres = $book->genres()->pluck('genres.id')->all();
        $alreadyAttached = empty(array_diff($genres, $attachedGenres));

        if($alreadyAttached){
            return true;
        }

        $newGenres = array_diff($genres, $attachedGenres);
        $book->genres()->attach($newGenres);
        return true;
    }

    public function detachFromBook(int $bookId, array $genres): bool
    {
        $book = $this->bookRepository->find($bookId);
        if (!$book) {
            throw new ApiException("Книга не найдена");
        }

        $exists = Genre::whereIn('id', $genres)->pluck('id')->all();

        if (count($exists) !== count($genres)) {
            throw new ApiException("Один или более жанров не существуют. Пожалуйста, укажите корректные жанры");
        }

        $attachedGenres = $book->genres()->pluck('genres.id')->all();
        $genresToDetach = empty(array_diff($genres, $attachedGenres));
        
        if(!$genresToDetach){
            throw new ApiException('Указанные жанры не привязаны к книге');
        }

        $book->genres()->detach($genres);

        return true;
    }

    public function delete(int $id): bool
    {
        $genre = $this->genreRepository->find($id);
        if (!$genre) {
            throw new ApiException("Жанр не найден");
        }
        return $this->genreRepository->delete($genre);
    }

}
