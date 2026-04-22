<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class CategoryService implements CategoryServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->categoryRepository->all();
    }

    public function getPaginated(?array $data, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $data['q'] = Str::slug($data['q'] ?? '');
        $perPage = $data['perPage'] ?? 10;
        return $this->categoryRepository->getPaginated($data, $perPage, $includeTrashed);
    }

    public function getAdminFiltered(?array $data): Collection
    {
        $data['q'] = Str::slug($data['q'] ?? '');
        return $this->categoryRepository->getAdminFiltered($data);
    }

    public function getById(int $id): Category
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new ApiException('Категория не найдена');
        }
        return $category;
    }

    public function getBySlug(string $slug): Category
    {
        $category = $this->categoryRepository->findBySlug($slug);
        if (!$category) {
            throw new ApiException('Категория не найдена');
        }
        return $category;
    }

    public function getByQuery(?string $query): Collection
    {
        $slug = Str::slug($query);

        $categories = $this->categoryRepository->getBySlug($slug);
        if (!$categories) {
            throw new ApiException("Категории не найдены");
        }
        return $categories;
    }

    public function create(array $data): Category
    {

        $slug = Str::slug($data['name']);
        $data['slug'] = $slug;

        $category = $this->categoryRepository->findBySlug($slug);
        if ($category) {
            throw new ApiException('Категория уже существует');
        }

        return $this->categoryRepository->create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new ApiException('Категория не найдена');
        }

        $slug = Str::slug($data['name']);
        $data['slug'] = $slug;

        return $this->categoryRepository->update($category, $data);
    }

    public function delete(int $id): bool
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            throw new ApiException('Категория не найдена');
        }

        return $this->categoryRepository->delete($category);
    }

    public function restore(int $id): bool
    {
        $category = Category::withTrashed()->find($id);
        if (!$category) {
            throw new ApiException("Удаленная категория не найдена");
        }

        return $this->categoryRepository->restore($category);
    }
}
