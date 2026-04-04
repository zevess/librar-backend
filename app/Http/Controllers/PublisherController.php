<?php

namespace App\Http\Controllers;

use App\Http\Requests\Publisher\GetPublisherRequest;
use App\Http\Requests\Publisher\StorePublisherRequest;
use App\Http\Resources\Publisher\PublisherCollection;
use App\Http\Resources\Publisher\PublisherResource;
use App\Services\Interfaces\PublisherServiceInterface;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function __construct(
        private PublisherServiceInterface $publisherService
    ) {
    }

    public function index(GetPublisherRequest $request)
    {
        $publishers = $this->publisherService->getPaginated($request->validated());
        return new PublisherCollection($publishers);
    }

    public function getAll()
    {
        $publishers = $this->publisherService->getAll();
        return new PublisherCollection($publishers);
    }

    public function show(int $id): PublisherResource
    {
        $publisher = $this->publisherService->getById($id);
        return new PublisherResource($publisher);
    }

    public function showBySlug(string $slug, int $id): PublisherResource
    {
        $publisher = $this->publisherService->getBySlugAndId($slug, $id);
        return new PublisherResource($publisher);

    }

    public function getByQuery(Request $request)
    {
        $query = $request->input('q');
        $publishers = $this->publisherService->getByQuery($query);
        return new PublisherCollection($publishers);
    }

    public function store(StorePublisherRequest $request): PublisherResource
    {
        $publisher = $this->publisherService->create($request->validated());
        return new PublisherResource($publisher);

    }

    public function update(int $id, StorePublisherRequest $request): PublisherResource
    {
        $publisher = $this->publisherService->update($id, $request->validated());
        return new PublisherResource($publisher);
    }

    public function destroy(int $id)
    {
        $this->publisherService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }

    public function restore(int $id)
    {
        $restored = $this->publisherService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }
}
