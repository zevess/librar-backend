<?php

namespace App\Http\Controllers;

use App\Http\Requests\Review\GetReviewRequest;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Resources\Review\ReviewCollection;
use App\Http\Resources\Review\ReviewResource;
use App\Http\Resources\Review\ReviewSummaryCollection;
use App\Http\Resources\Review\ReviewSummaryResource;
use App\Services\Interfaces\ReviewServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewServiceInterface $reviewService
    ) {
    }

    #[OA\Get(
        path: '/api/reviews',
        operationId: 'getReviews',
        tags: ['Reviews'],
        summary: 'Получение отзывов',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReviewsResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function index(): ReviewCollection
    {
        $reviews = $this->reviewService->getAll();
        return new ReviewCollection($reviews);
    }

    #[OA\Get(
        path: '/api/admin/reviews',
        operationId: 'getAdminReviews',
        tags: ['Reviews'],
        summary: 'Получение отзывов для админа',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/SearchQuery'),
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/BookIdQuery'),
            new OA\Parameter(ref: '#/components/parameters/EmailQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReviewsResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function adminPaginated(GetReviewRequest $request): ReviewCollection
    {
        $reviews = $this->reviewService->getPaginated($request->validated(), true);
        return new ReviewCollection($reviews);
    }

    public function show(int $id): ReviewResource
    {
        $review = $this->reviewService->getById($id);
        return new ReviewResource($review);
    }

    #[OA\Get(
        path: '/api/books/{bookId}/reviews',
        operationId: 'getBooksReviews',
        tags: ['Reviews'],
        summary: 'Получение отзывов книги',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/BookIdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReviewsSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function showByBook(int $bookId): ReviewSummaryCollection
    {
        $reviews = $this->reviewService->getByBook($bookId);
        $average = $reviews->avg('rating');

        $userId = Auth::guard('sanctum')->id();
        $hasUserReviewed = false;
        if ($userId) {
            $hasUserReviewed = $reviews->where('user_id', $userId)->isNotEmpty();
        }

        return (new ReviewSummaryCollection($reviews))->additional([
            'average' => $average,
            'hasUserReviewed' => $hasUserReviewed
        ]);
    }

    #[OA\Get(
        path: '/api/reviews/user/{userId}',
        operationId: 'getUserReviews',
        tags: ['Reviews'],
        summary: 'Получение отзывов пользователя',
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/UserIdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReviewsSummaryResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function showByUser(int $userId): ReviewSummaryCollection
    {
        $reviews = $this->reviewService->getByUser($userId);
        return new ReviewSummaryCollection($reviews);
    }


    #[OA\Post(
        path: '/api/books/{bookId}/reviews',
        summary: 'Создать отзыв',
        operationId: 'createReview',
        tags: ['Reviews'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreReviewRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/ReviewResponse'),
        ]
    )]
    public function store(int $bookId, StoreReviewRequest $request): ReviewResource
    {
        $review = $this->reviewService->create(auth()->id(), $bookId, $request->validated());
        return new ReviewResource($review);
    }

    #[OA\Put(
        path: '/api/reviews/{id}',
        summary: 'Изменить отзыв',
        operationId: 'updateReview',
        tags: ['Reviews'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/StoreReviewRequest')
        ),
        responses: [
            new OA\Response(response: 201, ref: '#/components/responses/ReviewResponse'),
        ]
    )]
    public function update(int $id, StoreReviewRequest $request): ReviewResource
    {
        $review = $this->reviewService->update($id, $request->validated());
        return new ReviewResource($review);
    }


    #[OA\Delete(
        path: '/api/reviews/{id}',
        summary: 'Удалить отзыв',
        operationId: 'deleteReview',
        tags: ['Reviews'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 201, description: 'Удалено'),
        ]
    )]
    public function destroy(int $id): JsonResponse
    {
        $this->reviewService->delete($id);
        return response()->json([
            "message" => "Удалено"
        ]);
    }

    #[OA\Post(
        path: '/api/reviews/{id}',
        summary: 'Восстановить отзыв',
        operationId: 'restoreReview',
        tags: ['Reviews'],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 201, description: 'Восстановлено'),
        ]
    )]
    public function restore(int $id): JsonResponse
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
