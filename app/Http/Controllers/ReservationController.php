<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\ReservationRequest;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Services\Interfaces\ReservationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationServiceInterface $reservationService
    ) {
    }

    public function index(ReservationRequest $request): ReservationCollection
    {
        $reservations = $this->reservationService->getPaginated($request->validated());
        return new ReservationCollection($reservations);
    }

    public function show(int $id): ReservationResource
    {
        $reservation = $this->reservationService->getById($id);
        return new ReservationResource($reservation);
    }

    public function showByUser(int $userId): ReservationCollection
    {
        $data['userId'] = $userId;
        $reservations = $this->reservationService->getByUser($userId);
        return new ReservationCollection($reservations);
    }

    public function reserve(int $bookId): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->reserve($bookId, auth()->id());
        return response()->json([
            "message" => "Книга забронирована",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    public function cancel(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->cancel($id);
        return response()->json([
            "message" => "Бронь отменена",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    public function issue(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->issue($id);
        return response()->json([
            "message" => "Книга выдана",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    public function accept(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->accept($id);
        return response()->json([
            "message" => "Книга принята",
            "reservation" => new ReservationResource($reservation)
        ]);
    }
    public function cancelExpired()
    {
        return $this->reservationService->cancelExpired();
    }
}
