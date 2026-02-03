<?php

namespace App\Services\Interfaces;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Support\Collection;

interface ReservationServiceInterface
{
    public function getAll(): Collection;

    public function getById(int $id): ?Reservation;
    
    public function getByUserId(int $userId): Collection;

    public function getByBookId(int $bookId): Collection;

    public function getByBookIdAndStatus(int $bookId, ReservationStatus $status): ?Reservation;

    public function reserve(int $bookId, int $userId): Reservation;

    public function issue(int $bookId): Reservation;

    public function accept(int $bookId): Reservation;

    public function expiration();

    public function update(Reservation $reservation, array $data): Reservation;
}
