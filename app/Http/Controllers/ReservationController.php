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

    public function show(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->getById($id);

        $reservation->load('book');
        $reservation->load('reservedBy');

        return new ReservationResource($reservation);
    }

    public function showByUser(int $userId)
    {
        $data['userId'] = $userId;
        $reservations = $this->reservationService->getPaginated($data);
        return new ReservationCollection($reservations);
    }

    public function reserve(int $bookId): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->reserve($bookId, auth()->id());

        $reservation->load('book');

        return response()->json([
            "message" => "Книга забронирована",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    public function cancel(int $id)
    {
        $reservation = $this->reservationService->cancel($id);
        $reservation->load('book');

        return response()->json([
            "message" => "Бронь отменена",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    public function issue(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->issue($id);
        $reservation->load('book');

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
