<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Services\Interfaces\CategoryServiceInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $categoryService
    ){}

    public function index()
    {
        $categories = $this->categoryService->getAll();
        return $categories;
    }

    public function show(int $id)
    {
        $category = $this->categoryService->getById($id);
        return $category;
    }

    public function showBySlug(int $slug)
    {
        $category = $this->categoryService->getBySlug($slug);
        return $category;
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());
        return $category;
    }

    public function update(int $id, StoreCategoryRequest $request)
    {
        $category = $this->categoryService->update($id, $request->validated());
        return $category;
    }

    public function destroy(int $id)
    {
        $this->categoryService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }
}
