<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(): Collection
    {
        return Category::oldest()->get();
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function findBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }

    public function getPaginated(?array $data, int $perPage, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $result = Category::when($id, fn($q) => $q->where('id', $id))->when($search, fn($q) => $q->where('slug', 'like', "%{$search}%"))->withTrashed($includeTrashed);
        return $result->paginate($perPage)->withQueryString();
    }

    public function getAdminFiltered(?array $data): Collection
    {
        $search = $data['q'] ?? '';
        $id = $data['id'] ?? '';
        $result = Category::query()->when($id, fn($q) => $q->where('id', $id))->when($search, fn($q) => $q->where('slug', 'like', "%{$search}%"))->withTrashed()->get();
        return $result;
    }

    public function getBySlug(?string $slug): Collection
    {
        return Category::query()->where('slug', 'like', "%{$slug}%")->take(10)->get();
    }

    public function update(Category $category, array $data): Category
    {
        $category->update($data);
        return $category;
    }

    public function delete(Category $category): bool
    {
        return $category->delete();
    }

    public function restore(Category $category): bool
    {
        return $category->restore();
    }
}
