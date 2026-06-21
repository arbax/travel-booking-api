<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PassengerController;
use App\Http\Controllers\Api\TicketController;




Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    Route::apiResource('bookings', BookingController::class)->except(['destroy']);
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    Route::get('bookings/{booking}/passengers', [PassengerController::class, 'index']);
    Route::post('bookings/{booking}/passengers', [PassengerController::class, 'store']);
    Route::delete('bookings/{booking}/passengers/{passenger}', [PassengerController::class, 'destroy']);

    Route::get('bookings/{booking}/passengers/{passenger}/ticket', [TicketController::class, 'show']);
    Route::post('bookings/{booking}/passengers/{passenger}/ticket/issue', [TicketController::class, 'issue']);


});