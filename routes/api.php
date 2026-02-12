<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
    });

    Route::get('me', [AuthController::class, 'me'])->middleware('auth:sanctum');
});

Route::prefix('books')->group(function () {

    Route::prefix('reservations')->group(function () {
        Route::post('/{book}/reserve', [ReservationController::class, 'reserve'])->middleware('auth:sanctum');

        Route::get('/', [ReservationController::class, 'index'])->middleware(['auth:sanctum', 'role:admin,librarian']);
    });

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {
        Route::post('/', [BookController::class, 'store']);
        Route::put('/{id}', [BookController::class, 'update']);
        Route::delete('/{id}', [BookController::class, 'destroy']);
        Route::post('/{id}/restore', [BookController::class, 'restore']);
    });

    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::get('/{id}/reviews', [ReviewController::class, 'showByBook']);

});

Route::prefix('genres')->group(function () {

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {
        Route::post('/{genreName}', [GenreController::class, 'store']);
        Route::post('/attach/{bookId}', [GenreController::class, 'attach']);
        Route::delete('/detach/{bookId}', [GenreController::class, 'detach']);
        Route::delete('/{id}', [GenreController::class, 'destroy']);
    });

    Route::get('/', [GenreController::class, 'index']);
    Route::get('/{id}', [GenreController::class, 'show']);
});

Route::prefix('reservations')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/{id}', [ReservationController::class, 'show']);
        Route::get('/{user}', [ReservationController::class, 'showByUser']);
        Route::post('/{id}/cancel', [ReservationController::class, 'cancel']);
    });

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {
        Route::get('/', [ReservationController::class, 'index']);
        Route::post('/{id}/issue', [ReservationController::class, 'issue']);
        Route::post('/{id}/accept', [ReservationController::class, 'accept']);
    });

});

Route::prefix('publishers')->group(function () {
    Route::get('/', [PublisherController::class, 'index']);
    Route::get('/{id}', [PublisherController::class, 'show']);

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {
        Route::post('/', [PublisherController::class, 'store']);
        Route::put('/{id}', [PublisherController::class, 'update']);
        Route::delete('/{id}', [PublisherController::class, 'destroy']);
    });
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('/{id}', [CategoryController::class, 'show']);

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });
});

Route::prefix('authors')->group(function () {
    Route::get('/', [AuthorController::class, 'index']);
    Route::get('/{id}', [AuthorController::class, 'show']);

    Route::middleware(['auth:sanctum', 'role:admin,librarian'])->group(function () {
        Route::post('/', [AuthorController::class, 'store']);
        Route::put('/{id}', [AuthorController::class, 'update']);
        Route::delete('/{id}', [AuthorController::class, 'destroy']);
    });
});

Route::prefix('reviews')->group(function () {
    Route::get('/', [ReviewController::class, 'index']);
    Route::post('/', [ReviewController::class, 'store'])->middleware(['auth:sanctum', 'role:admin,librarian']);

    Route::get('/{id}', [ReviewController::class, 'show']);

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