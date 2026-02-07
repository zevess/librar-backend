<?php

namespace App\Http\Controllers;

use App\Http\Requests\Publisher\StorePublisherRequest;
use App\Services\Interfaces\PublisherServiceInterface;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function __construct(
        private PublisherServiceInterface $publisherService
    ){}

    public function index()
    {
        $publishers = $this->publisherService->getAll();
        return $publishers;
    }

    public function show(int $id)
    {
        $publisher = $this->publisherService->getById($id);
        return $publisher;
    }

    public function showBySlug(int $slug)
    {
        $publisher = $this->publisherService->getBySlug($slug);
        return $publisher;
    }

    public function store(StorePublisherRequest $request)
    {
        $publisher = $this->publisherService->create($request->validated());
        return $publisher;
    }

    public function update(int $id, StorePublisherRequest $request)
    {
        $publisher = $this->publisherService->update($id, $request->validated());
        return $publisher;
    }

    public function destroy(int $id)
    {
        $this->publisherService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }
}
