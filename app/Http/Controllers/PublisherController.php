<?php

namespace App\Http\Controllers;

use App\Http\Requests\Publisher\GetPublisherRequest;
use App\Http\Requests\Publisher\StorePublisherRequest;
use App\Http\Resources\Publisher\PublisherCollection;
use App\Http\Resources\Publisher\PublisherResource;
use App\Http\Resources\Publisher\PublisherSummaryCollection;
use App\Http\Resources\Publisher\PublisherSummaryResource;
use App\Services\Interfaces\PublisherServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class PublisherController extends Controller
{
    public function __construct(
        private PublisherServiceInterface $publisherService
    ) {
    }

    #[OA\Get(
        path: '/api/publishers',
        operationId: 'getPublishers',
        tags: ['Publishers'],
        summary: 'Получение издательств',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/PublishersResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function index(GetPublisherRequest $request): PublisherCollection
    {
        $publishers = $this->publisherService->getPaginated($request->validated());
        return new PublisherCollection($publishers);
    }

    #[OA\Get(
        path: '/api/admin/publishers',
        operationId: 'getAdminPublishers',
        tags: ['Publishers'],
        summary: 'Получение издательств',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/PublishersResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function adminPaginated(GetPublisherRequest $request): PublisherCollection
    {
        $publishers = $this->publisherService->getPaginated($request->validated(), true);
        return new PublisherCollection($publishers);
    }

    #[OA\Get(
        path: '/api/publishers/get',
        operationId: 'getAllPublishers',
        tags: ['Publishers'],
        summary: 'Получение всех издательств',
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/PublishersSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function getAll(): PublisherSummaryCollection
    {
        $publishers = $this->publisherService->getAll();
        return new PublisherSummaryCollection($publishers);
    }

    #[OA\Get(
        path: '/api/publishers/{id}',
        operationId: 'getPublisherById',
        tags: ['Publishers'],
        summary: 'Получение издательства',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/PublisherResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function show(int $id): PublisherResource
    {
        $publisher = $this->publisherService->getById($id);
        return new PublisherResource($publisher);
    }

    #[OA\Get(
        path: '/api/publishers/{slug}-{id}',
        operationId: 'getPublisherBySlugAndId',
        tags: ['Publishers'],
        summary: 'Получение издательства',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SlugInPath'),
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/PublisherResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function showBySlugAndId(string $slug, int $id): PublisherResource
    {
        $publisher = $this->publisherService->getBySlugAndId($slug, $id);
        return new PublisherResource($publisher);

    }

    #[OA\Get(
        path: '/api/publishers/query',
        operationId: 'getPublishersByQuery',
        tags: ['Publishers'],
        summary: 'Поиск издательств',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/PublishersSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function getByQuery(Request $request): PublisherSummaryCollection
    {
        $query = $request->input('q');
        $publishers = $this->publisherService->getByQuery($query);
        return new PublisherSummaryCollection($publishers);
    }

    #[OA\Post(
        path: '/api/publishers',
        summary: 'Создать издателя',
        operationId: 'createPublisher',
        tags: ['Publishers'],
        security: [
            ['bearerAuth' => []]
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StorePublisherRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/PublisherSummaryResponse'),
        ]
    )]
    public function store(StorePublisherRequest $request): PublisherSummaryResource
    {
        $publisher = $this->publisherService->create($request->validated());
        return new PublisherSummaryResource($publisher);

    }

    #[OA\Put(
        path: '/api/publishers/{id}',
        summary: 'Изменить издателя',
        operationId: 'updatePublisher',
        tags: ['Publishers'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StorePublisherRequest')
        ),
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/PublisherSummaryResponse'),
        ]
    )]
    public function update(int $id, StorePublisherRequest $request): PublisherSummaryResource
    {
        $publisher = $this->publisherService->update($id, $request->validated());
        return new PublisherSummaryResource($publisher);
    }

    #[OA\Delete(
        path: '/api/publishers/{id}',
        summary: 'Удалить издателя',
        operationId: 'deletePublisher',
        tags: ['Publishers'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Удалено'),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->publisherService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }

    #[OA\Post(
        path: '/api/publishers/{id}/restore',
        summary: 'Восстановить издателя',
        operationId: 'restorePublisher',
        tags: ['Publishers'],
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Восстановлено'),
        ]
    )]
    public function restore(int $id): JsonResponse
    {
        $restored = $this->publisherService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);
        $result = $this->publisherService->import($request->file('file'));
        return response()->json($result);
    }
}
