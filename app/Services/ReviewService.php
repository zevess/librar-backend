<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Review;
use App\Repositories\Interfaces\ReviewRepositoryInterface;
use App\Services\Interfaces\ReviewServiceInterface;
use Illuminate\Database\Eloquent\Collection;

class ReviewService implements ReviewServiceInterface
{
    public function __construct(
        private ReviewRepositoryInterface $reviewRepository
    ){}

    public function getAll(): Collection
    {
        return $this->reviewRepository->all();
    }

    public function getById(int $id): ?Review
    {
        $review = $this->reviewRepository->find($id);

        if(!$review){
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

    public function create(array $data, int $userId): Review
    {
        $existingReview = $this->reviewRepository->findByBookAndUser($data['book_id'], $userId);

        if($existingReview){
            throw new ApiException('Отзыв уже оставлен');
        }

        $data['user_id'] = $userId;
        return $this->reviewRepository->create($data);
    }

    public function update(int $id, array $data): Review
    {
        $review = $this->reviewRepository->find($id);
        if(!$review){
            throw new ApiException('Отзыв не найден');
        }
        return $this->reviewRepository->update($review, $data);
    }

    public function delete(int $id): bool
    {
        $review = $this->reviewRepository->find($id);
        if(!$review){
            throw new ApiException('Отзыв не найден');
        }
        return $this->reviewRepository->delete($review);
    }
}
