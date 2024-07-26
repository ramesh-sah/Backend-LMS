<?php

use App\Http\Controllers\Api\NewBookRequest\Controller\NewBookRequestController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for NewBookRequest
Route::prefix('/admin/newBookRequest')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllNewBookRequest', [NewBookRequestController::class, 'getAllNewBookRequest']);
    Route::get('/{request_id}', [NewBookRequestController::class, 'getNewBookRequest']);
    Route::put('/{request_id}', [NewBookRequestController::class, 'updateNewBookRequest']);
    Route::delete('/{request_id}', [NewBookRequestController::class, 'destroyNewBookRequest']);
});

Route::prefix('/employee/newBookRequest')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllNewBookRequest', [NewBookRequestController::class, 'getAllNewBookRequest']);
    Route::get('/{request_id}', [NewBookRequestController::class, 'getNewBookRequest']);
    Route::post('/', [NewBookRequestController::class, 'postNewBookRequest']);
    Route::put('/{request_id}', [NewBookRequestController::class, 'updateNewBookRequest']);
    Route::delete('/{request_id}', [NewBookRequestController::class, 'destroyNewBookRequest']);
});

Route::prefix('/member/newBookRequest')->middleware(['auth:member'])->group(function () {
    Route::get('/{request_id}', [NewBookRequestController::class, 'getNewBookRequest']);
    Route::post('/', [NewBookRequestController::class, 'postNewBookRequest']);
    Route::put('/{request_id}', [NewBookRequestController::class, 'updateNewBookRequest']);
    Route::delete('/{request_id}', [NewBookRequestController::class, 'destroyNewBookRequest']);
});