<?php

namespace App\Services\Interfaces;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Support\Collection;

interface ReservationServiceInterface
{
    public function getAll(): Collection;

    public function getFiltered(?array $data): Collection;

    public function getById(int $id): ?Reservation;
    
    // public function getByUser(int $userId, ?array $data): Collection;

    // public function getByBookId(int $bookId): Collection;

    // public function getByBookIdAndStatus(int $bookId, ReservationStatus $status): ?Reservation;

    public function reserve(int $bookId, int $userId): Reservation;

    public function cancel(int $reservationId): Reservation;

    public function issue(int $reservationId): Reservation;

    public function accept(int $reservationId): Reservation;

    public function cancelExpired();

    public function update(Reservation $reservation, array $data): Reservation;
}
