<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Route::prefix('books')->group(function () {
Route::middleware('auth:sanctum')->group(function () {
    // Route::post('books', [BookController::class, 'store']);
    Route::apiResource('books', BookController::class)->except(['create', 'edit']);

});
// });

