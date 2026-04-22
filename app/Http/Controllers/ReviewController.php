<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\GetReviewRequest;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Resources\Review\ReviewCollection;
use App\Http\Resources\Review\ReviewResource;
use App\Services\Interfaces\ReviewServiceInterface;
use Illuminate\Support\Facades\Auth;

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

    public function adminPaginated(GetReviewRequest $request): ReviewCollection
    {
        $reviews = $this->reviewService->getPaginated($request->validated(), true);
        return new ReviewCollection($reviews);
    }

    public function show(int $id)
    {
        $review = $this->reviewService->getById($id);
        return new ReviewResource($review);
    }

    public function showByBook(int $bookId)
    {
        $reviews = $this->reviewService->getByBook($bookId);
        $reviews->load('user');
        $average = $reviews->avg('rating');

        $userId = Auth::guard('sanctum')->id();
        $hasUserReviewed = false;
        if ($userId) {
            $hasUserReviewed = $reviews->where('user_id', $userId)->isNotEmpty();
        }

        return (new ReviewCollection($reviews))->additional([
            'average' => $average,
            'hasUserReviewed' => $hasUserReviewed
        ]);
    }

    public function showByUser(int $userId)
    {
        $reviews = $this->reviewService->getByUser($userId);
        return new ReviewCollection($reviews);
    }

    public function store(int $bookId, StoreReviewRequest $request)
    {
        $review = $this->reviewService->create(auth()->id(), $bookId, $request->validated());
        return $review;
    }

    public function update(int $id, StoreReviewRequest $request): ReviewResource
    {
        $review = $this->reviewService->update($id, $request->validated());
        return new ReviewResource($review);
    }

    public function destroy(int $id)
    {
        $this->reviewService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }

    public function restore(int $id)
    {
        $restored = $this->reviewService->restore($id);
        if (!$restored) {
            return response()->json(["message" => "Ошибка при восстановлении"], 404);
        }

        return response()->json([
            "message" => "Восстановлено"
        ]);
    }
}
