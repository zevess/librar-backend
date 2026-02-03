<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
    });
});

Route::prefix('books')->group(function () {

    Route::middleware('auth:sanctum')->group(callback: function () {
        Route::post('/{id}/reserve', [ReservationController::class, 'reserve']);
    });

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {

        Route::prefix('reservations')->group(function () {
            Route::get('/', [ReservationController::class, 'index']);
            Route::get('/{id}', [ReservationController::class, 'show']);
        });

        Route::post('/', [BookController::class, 'store']);
        Route::put('/{id}', [BookController::class, 'update']);
        Route::delete('/{id}', [BookController::class, 'delete']);
        Route::post('/{id}/restore', [BookController::class, 'restore']);

        Route::post('/{id}/issue', [ReservationController::class, 'issue']);
        Route::post('/{id}/accept', [ReservationController::class, 'accept']);

    });

    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);

});



Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'index']);
    Route::get('/{id}', [AuthorController::class, 'show']);

    Route::get('/{id}/books', [AuthorController::class, 'showWithBooks']);

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {
        Route::post('/', [AuthorController::class, 'store']);
        Route::put('/{id}', [AuthorController::class, 'update']);
        Route::delete('/{id}', [AuthorController::class, 'destroy']);
    });
});





Route::prefix('admin')->middleware(['auth:sanctum', 'role:admin'])->group(function () {

    Route::prefix('users')->group(function () {

        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);

        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::put('/role/{id}', [UserController::class, 'updateRole']);

        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::post('/{id}/restore', [UserController::class, 'restore']);

    });

});