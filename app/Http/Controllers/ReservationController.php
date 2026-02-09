<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\ReservationCollection;
use App\Http\Resources\ReservationResource;
use App\Services\Interfaces\ReservationServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationServiceInterface $reservationService
    ) {
    }

    public function index(): ReservationCollection
    {
        $reservations = $this->reservationService->cancelExpired();
        return new ReservationCollection($reservations);
    }

    public function show(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->getById($id);

        $reservation->load('book');
        $reservation->load('reservedBy');

        return new ReservationResource($reservation);
    }

    public function reserve(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->reserve($id, auth()->id());

        $reservation->load('book');

        return response()->json([
            "message" => "Книга забронирована",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    public function cancel(int $id)
    {
        $reservation = $this->reservationService->cancel($id, auth()->id());
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
        $reservation->load('book');

        return response()->json([
            "message" => "Книга принята",
            "reservation" => new ReservationResource($reservation)
        ]);
    }
}
