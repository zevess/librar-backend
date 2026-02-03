<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Exceptions\ApiException;
use App\Models\Reservation;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Services\Interfaces\ReservationServiceInterface;
use Illuminate\Support\Collection;

use function Illuminate\Support\now;

class ReservationService implements ReservationServiceInterface
{

    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private BookRepositoryInterface $bookRepository
    ) {
    }

    public function getAll(): Collection
    {
        return $this->reservationRepository->all();
    }

    public function getById(int $id): ?Reservation
    {
        return $this->reservationRepository->find($id);
    }

    public function getByUserId(int $userId): Collection
    {
        return $this->reservationRepository->findByUserId($userId);
    }

    public function getByBookId(int $bookId): Collection
    {
        return $this->reservationRepository->findByBookId($bookId);
    }

    public function getByBookIdAndStatus(int $bookId, ReservationStatus $status): Reservation
    {
        return $this->reservationRepository->findByBookIdAndStatus($bookId, $status);
    }

    public function reserve(int $bookId, int $userId): Reservation
    {

        $book = $this->bookRepository->find($bookId);

        if (!$book) {
            throw new ApiException("Книга не найдена");
        }

        $isReserved = $this->reservationRepository->findByBookIdAndStatus($bookId, ReservationStatus::RESERVED);

        if ($isReserved) {
            throw new ApiException("Книга уже забронирована");
        }

        $isIssued = $this->reservationRepository->findByBookIdAndStatus($bookId, ReservationStatus::ISSUED);

        if($isIssued){
            throw new ApiException("Книга уже выдана");
        }
        
        $data['book_id'] = $bookId;
        $data['reserved_by'] = $userId;
        $data['reserved_at'] = now();
        $data['expires_at'] = now()->addDays(3)->toDateString();
        $data['status'] = ReservationStatus::RESERVED->value;
        return $this->reservationRepository->create($data);
    }

    public function issue(int $bookId): Reservation
    {
        $reservedBook = $this->reservationRepository->findByBookIdAndStatus($bookId, ReservationStatus::RESERVED);

        $isIssued = $this->reservationRepository->findByBookIdAndStatus($bookId, ReservationStatus::ISSUED);

        if($isIssued){
            throw new ApiException("Книга уже выдана");
        }

        if(!$reservedBook){
            throw new ApiException("Забронированная книга не найдена");
        }
        

        $data['status'] = ReservationStatus::ISSUED->value;
        $data['issued_at'] = now();
        $data['expires_at'] = null;

        return $this->reservationRepository->update($reservedBook, $data);

    }

    public function accept(int $bookId): Reservation
    {
        $issuedBook = $this->reservationRepository->findByBookIdAndStatus($bookId, ReservationStatus::ISSUED);

        if(!$issuedBook){
            throw new ApiException("Книгу уже приняли");
        }

        $data['status'] = ReservationStatus::COMPLETED->value;
        $data['accepted_at'] = now();

        return $this->reservationRepository->update($issuedBook, $data);
    }

    public function expiration()
    {
        $activeReservations = $this->reservationRepository->findByStatus(ReservationStatus::RESERVED);
        
        foreach ($activeReservations as $reservation){
            if($reservation->expires_at < now()){
                $reservation->status = ReservationStatus::COMPLETED->value;
                $reservation->save();
            }
        }

        return $activeReservations;
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        return $this->reservationRepository->update($reservation, $data);
    }
}
