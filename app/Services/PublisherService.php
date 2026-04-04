<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Publisher;
use App\Repositories\Interfaces\PublisherRepositoryInterface;
use App\Services\Interfaces\PublisherServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class PublisherService implements PublisherServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private PublisherRepositoryInterface $publisherRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->publisherRepository->all();
    }

    public function getById(int $id): Publisher
    {
        $publisher = $this->publisherRepository->find($id);
        if (!$publisher) {
            throw new ApiException('Издательство не найдено');
        }
        return $publisher;
    }

    public function getBySlug(string $slug): Publisher
    {
        $publisher = $this->publisherRepository->findBySlug($slug);
        if (!$publisher) {
            throw new ApiException('Издательство не найдено');
        }
        return $publisher;
    }

    public function getBySlugAndId(string $slug, int $id): Publisher
    {
        $publisher = $this->publisherRepository->findBySlugAndId($slug, $id);
        if (!$publisher) {
            throw new ApiException('Издательство не найдено');
        }
        return $publisher;
    }

    public function getPaginated(?array $data): LengthAwarePaginator
    {
        $data['q'] = Str::slug($data['q'] ?? '');
        $perPage = $data['perPage'] ?? 10;

        return $this->publisherRepository->getPaginated($data, $perPage);
    }


    public function getByQuery(?string $query): Collection
    {
        $slug = Str::slug($query);

        $publishers = $this->publisherRepository->getBySlug($slug);
        if (!$publishers) {
            throw new ApiException("Издательства не найдены");
        }
        return $publishers;
    }

    public function create(array $data): Publisher
    {
        $slug = Str::slug($data['name']);
        $data['slug'] = $slug;

        $publisher = $this->publisherRepository->findBySlug($slug);
        if ($publisher) {
            throw new ApiException('Издательство уже существует');
        }

        return $this->publisherRepository->create($data);
    }

    public function update(int $id, array $data): Publisher
    {
        $publisher = $this->publisherRepository->find($id);
        if (!$publisher) {
            throw new ApiException('Издательство не найдено');
        }

        $slug = Str::slug($data['name']);
        $data['slug'] = $slug;

        return $this->publisherRepository->update($publisher, $data);
    }

    public function delete(int $id): bool
    {
        $publisher = $this->publisherRepository->find($id);
        if (!$publisher) {
            throw new ApiException('Издательство не найдено');
        }

        return $this->publisherRepository->delete($publisher);
    }

    public function restore(int $id): bool
    {
        $publisher = Publisher::withTrashed()->find($id);
        if (!$publisher) {
            throw new ApiException("Удаленное издательство не найдено");
        }
        return $this->publisherRepository->restore($publisher);
    }
}
