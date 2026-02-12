<?php

namespace App\Http\Controllers;

use App\Http\Requests\Publisher\StorePublisherRequest;
use App\Http\Resources\Publisher\PublisherResource;
use App\Services\Interfaces\PublisherServiceInterface;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function __construct(
        private PublisherServiceInterface $publisherService
    ) {
    }

    public function index()
    {
        $publishers = $this->publisherService->getAll();
        return $publishers;
    }

    public function show(int $id): PublisherResource
    {
        $publisher = $this->publisherService->getById($id);
        return new PublisherResource($publisher);
    }

    public function showBySlug(int $slug): PublisherResource
    {
        $publisher = $this->publisherService->getBySlug($slug);
        return new PublisherResource($publisher);

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
}
