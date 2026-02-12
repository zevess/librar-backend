<?php

namespace App\Repositories;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
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

    // public function findByUserId(int $userId): Collection
    // {
    //     return Reservation::where('reserved_by', $userId)->get();
    // }

    // public function findByBookId(int $bookId): Collection
    // {
    //     return Reservation::where('book_id', $bookId)->get();
    // }

    // public function findByStatus(ReservationStatus $status): Collection
    // {
    //     return Reservation::where('status', $status)->get();
    // }

    // public function findByBookIdAndStatus(int $bookId, ReservationStatus $status): ?Reservation
    // {
    //     return Reservation::where('book_id', $bookId)->where('status', $status)->first();
    // }

    public function findFiltered(?array $data): Collection
    {
        $userId = $data['user_id'] ?? '';
        $status = isset($data['status']) ? ReservationStatus::from($data['status']) : null;;
        $bookId = $data['book_id'] ?? '';

        return Reservation::with(['book', 'reservedBy'])->when($userId, fn($q) => $q->where('reserved_by', $userId))
            ->when($status, fn($q) => $q->where('status', $status->value))
            ->when($bookId, fn($q) => $q->where('book_id', $bookId))
            ->get();
    }

    // public function findByUser(int $userId, ?ReservationStatus $status = null, ?int $bookId = null): Collection
    // {
    //     return Reservation::where('reserved_by', $userId)
    //         ->when($status, fn($q) => $q->where('status', $status->value))
    //         ->when($bookId, fn($q) => $q->where('book_id', $bookId))
    //         ->get();
    // }

    // public function findByBookIdAndUserId(int $bookId, int $userId): ?Reservation
    // {
    //     return Reservation::where('book_id', $bookId)::where('reserved_by', $userId);
    // }

    public function create(array $data): Reservation
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data): Reservation
    {
        $reservation->update($data);
        return $reservation;
    }
}
