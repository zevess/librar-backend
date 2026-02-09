<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\StoreReviewRequest;
use App\Services\Interfaces\ReviewServiceInterface;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewServiceInterface $reviewService
    ) {
    }

    public function index()
    {
        $reviews = $this->reviewService->getAll();
        return $reviews;
    }

    public function show(int $id)
    {
        $review = $this->reviewService->getById($id);
        return $review;
    }

    public function showByBook(int $bookId)
    {
        $reviews = $this->reviewService->getByBook($bookId);
        $average = $reviews->avg('rating');

        return response()->json([
            'reviews' => $reviews,
            'average' => round($average, 1)
        ]);
    }

    public function store(StoreReviewRequest $request)
    {
        $review = $this->reviewService->create($request->validated(), auth()->id());
        return $review;
    }

    public function destroy(int $id)
    {
        $this->reviewService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }
}
