<?php

namespace App\Services;

use App\Enums\ReservationStatus;
use App\Exceptions\ApiException;
use App\Models\Reservation;
use App\Repositories\Interfaces\BookRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Services\Interfaces\ReservationServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

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
        $reservation = $this->reservationRepository->find($id);
        if (!$reservation) {
            throw new ApiException('Бронь не найдена');
        }
        return $reservation;
    }

     public function getFiltered(?array $data): Collection
    {
        $reservations = $this->reservationRepository->findFiltered($data);

        if ($reservations->isEmpty()) {
            throw new ApiException('Брони не найдены');
        }

        return $reservations;
    }

    public function reserve(int $bookId, int $userId): Reservation
    {

        $book = $this->bookRepository->find($bookId);

        if (!$book) {
            throw new ApiException("Книга не найдена");
        }

        $isReserved = $this->reservationRepository->findFiltered([
            'book_id' => $bookId,
            'status' => ReservationStatus::RESERVED->value
        ])->first();

        if ($isReserved) {
            throw new ApiException("Книга уже забронирована");
        }

        $isIssued = $this->reservationRepository->findFiltered([
            'book_id' => $bookId,
            'status' => ReservationStatus::ISSUED->value
        ])->first();

        if ($isIssued) {
            throw new ApiException("Книга уже выдана");
        }

        $data['book_id'] = $bookId;
        $data['reserved_by'] = $userId;
        $data['reserved_at'] = now();
        $data['expires_at'] = now()->addDays(3)->toDateString();
        $data['status'] = ReservationStatus::RESERVED->value;
        return $this->reservationRepository->create($data);
    }

    public function cancel(int $reservationId): Reservation
    {
        $reservedBook = $this->reservationRepository->find($reservationId);

        if (!$reservedBook) {
            throw new ApiException('Бронь не найдена');
        }


        $data['status'] = ReservationStatus::CANCELED->value;
        $data['expires_at'] = null;

        return $this->reservationRepository->update($reservedBook, $data);
    }

    public function issue(int $reservationId): Reservation
    {
        $reservedBook = $this->reservationRepository->find($reservationId);

        if (!$reservedBook) {
            throw new ApiException('Бронь не найдена');
        }

        if ($reservedBook->status === ReservationStatus::ISSUED) {
            throw new ApiException('Книга уже выдана');
        }

        if ($reservedBook->status === ReservationStatus::COMPLETED) {
            throw new ApiException('Книга уже принята');
        }

        if (!$reservedBook) {
            throw new ApiException("Забронированная книга не найдена");
        }


        $data['status'] = ReservationStatus::ISSUED->value;
        $data['issued_at'] = now();
        $data['expires_at'] = null;

        return $this->reservationRepository->update($reservedBook, $data);

    }

    public function accept(int $reservationId): Reservation
    {

        $issuedBook = $this->reservationRepository->find($reservationId);

        if ($issuedBook->status === ReservationStatus::COMPLETED) {
            throw new ApiException("Книгу уже приняли");
        }

        if ($issuedBook->status === ReservationStatus::RESERVED) {
            throw new ApiException("Книгу забронировали");
        }

        if ($issuedBook->status === ReservationStatus::CANCELED) {
            throw new ApiException("Бронь была отменена");
        }

        $data['status'] = ReservationStatus::COMPLETED->value;
        $data['accepted_at'] = now();

        return $this->reservationRepository->update($issuedBook, $data);
    }

    public function cancelExpired()
    {
        $activeReservations = $this->reservationRepository->findFiltered([
            'status' => ReservationStatus::RESERVED->value
        ]);

        foreach ($activeReservations as $reservation) {
            if ($reservation->expires_at < now()) {
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
