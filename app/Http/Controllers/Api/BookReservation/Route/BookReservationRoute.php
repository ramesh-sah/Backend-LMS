<?php

use App\Http\Controllers\Api\BookReservation\Controller\BookReservationController; // Correct import
use Illuminate\Support\Facades\Route;

// Define the routes for Publishers
Route::prefix('/admin/bookReservation')->middleware('auth:admin')->group(function () {
    Route::get('/getAllBookReservation', [BookReservationController::class, 'getAllBookReservation']);
    Route::post('/', [BookReservationController::class, 'postBookReservation']);
    Route::get('/{reservation_id}', [BookReservationController::class, 'getBookReservation']);
    Route::put('/{reservation_id}', [BookReservationController::class, 'updateBookReservation']);
    Route::delete('/{reservation_id}', [BookReservationController::class, 'destroyBookReservation']);
});

Route::prefix('/employee/bookReservation')->middleware('auth:employee')->group(function () {
    Route::get('/getAllBookReservation', [BookReservationController::class, 'getAllBookReservation']);
    Route::post('/', [BookReservationController::class, 'postBookReservation']);
    Route::get('/{reservation_id}', [BookReservationController::class, 'getBookReservation']);
    Route::put('/{reservation_id}', [BookReservationController::class, 'updateBookReservation']);
    Route::delete('/{reservation_id}', [BookReservationController::class, 'destroyBookReservation']);
});

Route::prefix('/member/bookReservation')->middleware('auth:member')->group(function () {
    Route::post('/', [BookReservationController::class, 'postBookReservation']);
    Route::get('/{reservation_id}', [BookReservationController::class, 'getBookReservation']);
    Route::get('/user/{member_id}', [BookReservationController::class, 'getSpecificUserAllBookReservation']);
    Route::delete('/{reservation_id}', [BookReservationController::class, 'destroyBookReservation']);
});


 