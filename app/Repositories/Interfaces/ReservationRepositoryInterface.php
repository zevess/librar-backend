<?php

namespace App\Repositories\Interfaces;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Illuminate\Support\Collection;

interface ReservationRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Reservation;
    
    public function findByUserId(int $userId): Collection;

    public function findByBookId(int $bookId): Collection;

    public function findByStatus(ReservationStatus $status): Collection;

    public function findByBookIdAndStatus(int $bookId, ReservationStatus $status): ?Reservation;

    public function create(array $data): Reservation;

    public function update(Reservation $reservation, array $data): Reservation;
}
