<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Services\Interfaces\ReviewServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class ReviewService implements ReviewServiceInterface
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->reviewRepository->all();
    }

    public function getById(int $id): ?Review
    {
        $review = $this->reviewRepository->find($id);

        if (!$review) {
            throw new ApiException('Отзыв не найден');
        }

        return $review;
    }

    public function getByBook(int $bookId): Collection
    {
        return $this->reviewRepository->findByBook($bookId);
    }

    public function getByUser(int $userId): Collection
    {
        return $this->reviewRepository->findByUser($userId);
    }

    public function getPaginated(?array $data, ?bool $includeTrashed = false): LengthAwarePaginator
    {
        $perPage = $data['perPage'] ?? 10;
        return $this->reviewRepository->getPaginated($data, $perPage, $includeTrashed);
    }

    public function create(int $userId, int $bookId, array $data): Review
    {
        $existingReview = $this->reviewRepository->findByBookAndUser($bookId, $userId);

        if ($existingReview) {
            throw new ApiException('Отзыв уже оставлен');
        }

        $data['user_id'] = $userId;
        $data['book_id'] = $bookId;
        return $this->reviewRepository->create($data);
    }

    public function update(int $id, array $data): Review
    {
        $review = $this->reviewRepository->find($id);
        if (!$review) {
            throw new ApiException('Отзыв не найден');
        }

        Gate::authorize('update', $review);

        return $this->reviewRepository->update($review, $data);
    }

    public function delete(int $id): bool
    {
        $review = $this->reviewRepository->find($id);
        if (!$review) {
            throw new ApiException('Отзыв не найден');
        }
        return $this->reviewRepository->delete($review);
    }

    public function restore(int $id): bool
    {
        $review = Review::withTrashed()->find($id);
        if (!$review) {
            throw new ApiException("Удаленный отзыв не найден");
        }

        return $this->reviewRepository->restore($review);
    }
}
