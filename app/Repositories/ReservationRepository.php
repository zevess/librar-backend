<?php

namespace App\Repositories;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function all(): Collection
    {
        return Reservation::latest()->get();
    }

    public function find(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function findFiltered(?array $data): Collection
    {
        $userId = $data['userId'] ?? '';
        $id = $data['id'] ?? '';
        $status = isset($data['status']) ? ReservationStatus::from($data['status']) : null;
        $bookId = $data['bookId'] ?? '';

        return Reservation::with(['book', 'reservedBy'])
            ->when($bookId, fn($q) => $q->where('book_id', $bookId))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($id, fn($q) => $q->where('id', $id))
            ->when($userId, fn($q) => $q->where('userId', $userId))
            ->get();
    }

    public function getPaginated(?array $data, int $perPage): LengthAwarePaginator
    {
        $user = $data['email'] ?? '';
        $userId = $data['userId'] ?? '';
        $id = $data['id'] ?? '';
        $bookId = $data['bookId'] ?? '';
        $status = isset($data['status']) ? ReservationStatus::from($data['status']) : null;

        $result = Reservation::with(['book', 'reservedBy'])
            ->when($id, fn($q) => $q->where('id', $id))
            ->when($user, function ($query) use ($user) {
                $query->whereHas('reservedBy', function ($q) use ($user) {
                    $q->where('email', 'like', "%{$user}%");
                });
            })
            ->when($status, fn($q) => $q->where('status', $status->value))
            ->when($bookId, fn($q) => $q->where('book_id', $bookId))
            ->when($userId, fn($q) => $q->where('reserved_by', $userId))
            ->latest();

        return $result->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation;
    }

    public function delete(Reservation $reservation): bool
    {
        return $reservation->delete();
    }
}
