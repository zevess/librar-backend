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
use OpenApi\Attributes as OA;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryServiceInterface $categoryService
    ) {
    }
    #[OA\Get(
        path: '/api/categories',
        operationId: 'getCategories',
        tags: ['Categories'],
        summary: 'Получение категорий',
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/CategoriesSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function index(): CategorySummaryCollection
    {
        $categories = $this->categoryService->getAll();
        return new CategorySummaryCollection($categories);
    }

    #[OA\Get(
        path: '/api/categories/{id}',
        operationId: 'getCategoryById',
        tags: ['Categories'],
        summary: 'Получение категории по ID',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/CategoryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function show(int $id): CategoryResource
    {
        $category = $this->categoryService->getById($id);
        return new CategoryResource($category);
    }

    public function adminPaginated(GetCategoryRequest $request): CategoryCollection
    {
        $categories = $this->categoryService->getPaginated($request->validated(), true);
        return new CategoryCollection($categories);
    }

    #[OA\Get(
        path: '/api/admin/categories',
        operationId: 'getAdminCategories',
        tags: ['Categories'],
        summary: 'Получение категории для админа',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/CategoriesResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function adminFiltered(GetCategoryRequest $request): CategoryCollection
    {
        $categories = $this->categoryService->getAdminFiltered($request->validated());
        return new CategoryCollection($categories);
    }

    #[OA\Get(
        path: '/api/categories/query',
        operationId: 'getCategoriesByQuery',
        tags: ['Categories'],
        summary: 'Получение категории для админа',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/CategoriesResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function getByQuery(Request $request): CategorySummaryCollection
    {
        $query = $request->input('q');
        $categories = $this->categoryService->getByQuery($query);
        return new CategorySummaryCollection($categories);
    }

    #[OA\Post(
        path: '/api/categories',
        summary: 'Создать категорию',
        operationId: 'createCategory',
        tags: ['Categories'],
        security: [
            ['bearerAuth' => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreCategoryRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/CategoryResponse'),
        ]
    )]
    public function store(StoreCategoryRequest $request): CategoryResource
    {
        $category = $this->categoryService->create($request->validated());
        return new CategoryResource($category);
    }

    #[OA\Put(
        path: '/api/categories/{id}',
        summary: 'Изменить категорию',
        operationId: 'updateCategory',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(ref: '#/components/schemas/StoreCategoryRequest')
        ),
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/CategoryResponse'),
        ]
    )]
    public function update(int $id, StoreCategoryRequest $request): CategoryResource
    {
        $category = $this->categoryService->update($id, $request->validated());
        return new CategoryResource($category);
    }

    #[OA\Delete(
        path: '/api/category/{id}',
        summary: 'Удалить категорию',
        operationId: 'deleteCategory',
        tags: ['Categories'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, description: "Удалено"),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->categoryService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }

    #[OA\Delete(
        path: '/api/category/{id}/restore',
        summary: 'Восстановить категорию',
        operationId: 'restoreCategory',
        tags: ['Categories'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, description: "Восстановлено"),
        ]
    )]
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
