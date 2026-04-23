<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\GetCategoryRequest;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Category\CategorySummaryCollection;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $categoryService
    ) {
    }

    public function index(): CategorySummaryCollection
    {
        $categories = $this->categoryService->getAll();
        return new CategorySummaryCollection($categories);
    }

    public function show(int $id): CategoryResource
    {
        $category = $this->categoryService->getById($id);
        return new CategoryResource($category);
    }

    public function showBySlug(int $slug): CategoryResource
    {
        $category = $this->categoryService->getBySlug($slug);
        return new CategoryResource($category);
    }

    public function adminPaginated(GetCategoryRequest $request): CategoryCollection
    {
        $categories = $this->categoryService->getPaginated($request->validated(), true);
        return new CategoryCollection($categories);
    }

    public function adminFiltered(GetCategoryRequest $request): CategoryCollection
    {
        $categories = $this->categoryService->getAdminFiltered($request->validated());
        return new CategoryCollection($categories);
    }

    public function getByQuery(Request $request): CategorySummaryCollection
    {
        $query = $request->input('q');
        $categories = $this->categoryService->getByQuery($query);
        return new CategorySummaryCollection($categories);
    }

    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = $this->categoryService->create($request->validated());
        return new CategoryResource($category);
    }

    public function update(int $id, StoreCategoryRequest $request): CategoryResource
    {
        $category = $this->categoryService->update($id, $request->validated());
        return new CategoryResource($category);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $restored = $this->categoryService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }
}
