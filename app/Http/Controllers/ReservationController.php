<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\ReservationRequest;
use App\Http\Resources\Reservation\ReservationCollection;
use App\Http\Resources\Reservation\ReservationResource;
use App\Services\Interfaces\ReservationServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class ReservationController extends Controller
{
    public function __construct(
        private ReservationServiceInterface $reservationService
    ) {
    }

    #[OA\Get(
        path: '/api/reservations',
        operationId: 'getReservations',
        tags: ['Reservations'],
        summary: 'Получение броней',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdQuery'),
            new OA\Parameter(ref: '#/components/parameters/BookIdQuery'),
            new OA\Parameter(ref: '#/components/parameters/UserIdQuery'),
            new OA\Parameter(ref: '#/components/parameters/EmailQuery'),
            new OA\Parameter(ref: '#/components/parameters/StatusQuery'),
            new OA\Parameter(ref: '#/components/parameters/PerPageQuery'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReservationsResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function index(ReservationRequest $request): ReservationCollection
    {
        $reservations = $this->reservationService->getPaginated($request->validated());
        return new ReservationCollection($reservations);
    }


    #[OA\Get(
        path: '/api/reservations/{id}',
        operationId: 'getReservationById',
        tags: ['Reservations'],
        summary: 'Получение брони',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReservationResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function show(int $id): ReservationResource
    {
        $reservation = $this->reservationService->getById($id);
        return new ReservationResource($reservation);
    }

    #[OA\Get(
        path: '/api/reservations/user/{userId}',
        operationId: 'getReservationByUserId',
        tags: ['Reservations'],
        summary: 'Получение брони пользователя',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/UserIdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReservationsResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function showByUser(int $userId): ReservationCollection
    {
        $reservations = $this->reservationService->getByUser($userId);
        return new ReservationCollection($reservations);
    }

    #[OA\Post(
        path: '/api/books/{bookId}/reserve',
        operationId: 'reserveBook',
        tags: ['Reservations'],
        summary: 'Забронировать книгу',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/BookIdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReservationResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function reserve(int $bookId): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->reserve($bookId, auth()->id());
        return response()->json([
            "message" => "Книга забронирована",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    #[OA\Post(
        path: '/api/reservations/{id}/cancel',
        operationId: 'cancelReservation',
        tags: ['Reservations'],
        summary: 'Отменить бронь книги',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReservationResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function cancel(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->cancel($id);
        return response()->json([
            "message" => "Бронь отменена",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    #[OA\Post(
        path: '/api/reservations/{id}/issue',
        operationId: 'issueReservation',
        tags: ['Reservations'],
        summary: 'Выдать книгу',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReservationResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function issue(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->issue($id);
        return response()->json([
            "message" => "Книга выдана",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    #[OA\Post(
        path: '/api/reservations/{id}/accept',
        operationId: 'acceptReservation',
        tags: ['Reservations'],
        summary: 'Принять книгу',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, ref: '#/components/responses/ReservationResponse'),
            new OA\Response(response: 404, ref: '#/components/responses/NotFound')
        ]
    )]
    public function accept(int $id): ReservationResource|JsonResponse
    {
        $reservation = $this->reservationService->accept($id);
        return response()->json([
            "message" => "Книга принята",
            "reservation" => new ReservationResource($reservation)
        ]);
    }

    #[OA\Post(
        path: '/api/reservations/cancel-expired',
        operationId: 'cancelExpiredReservation',
        tags: ['Reservations'],
        summary: 'Отменить просроченные брони',
        security: [
            ['bearerAuth' => []]
        ],
        parameters: [
            new OA\Parameter(ref: '#/components/parameters/IdInPath'),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Отменено'),
        ]
    )]
    public function cancelExpired()
    {
        return $this->reservationService->cancelExpired();
    }
}
