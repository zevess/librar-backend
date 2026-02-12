<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Http\Resources\Category\CategoryResource;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $categoryService
    ){}

    public function index(): CategoryCollection
    {
        $categories = $this->categoryService->getAll();
        return new CategoryCollection($categories);
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

    public function destroy(int $id)
    {
        $this->categoryService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }
}
