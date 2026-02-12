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
    ) {}

    public function index(Request $request): ReservationCollection
    {
        $data['status'] = $request->input('status');
        $data['book_id'] = $request->input('book_id');
        $data['user_id'] = $request->input('user_id');

        $reservations = $this->reservationService->getFiltered($data);
        return new ReservationCollection($reservations);
    }

    public function show(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->getById($id);

        $reservation->load('book');
        $reservation->load('reservedBy');

        return new ReservationResource($reservation);
    }

    public function showByUser(int $user, ReservationRequest $request)
    {
        $reservations = $this->reservationService->getByUser($user, $request->validated());
        return new ReservationCollection($reservations);
    }

    public function reserve(int $book): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->reserve($book, auth()->id());

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
