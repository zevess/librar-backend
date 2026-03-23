<?php

namespace App\Repositories\Interfaces;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ReservationRepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Reservation;

    public function findFiltered(?array $data): Collection;

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator;

    public function create(array $data): Reservation;

    public function update(Reservation $reservation, array $data): Reservation;

    public function delete(Reservation $reservation): bool;
}
