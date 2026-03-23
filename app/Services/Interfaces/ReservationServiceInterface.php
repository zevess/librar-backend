<?php

namespace App\Services\Interfaces;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ReservationServiceInterface
{
    public function getAll(): Collection;

    public function getFiltered(?array $data): Collection;

    public function getById(int $id): ?Reservation;

    public function getPaginated(?array $data): LengthAwarePaginator;

    public function reserve(int $bookId, int $userId): Reservation;

    public function cancel(int $reservationId): Reservation;

    public function issue(int $reservationId): Reservation;

    public function accept(int $reservationId): Reservation;

    public function cancelExpired();

    public function update(Reservation $reservation, array $data): Reservation;

    public function delete(int $id): bool;
}
