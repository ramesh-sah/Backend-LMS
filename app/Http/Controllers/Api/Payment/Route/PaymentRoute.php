<?php

use App\Http\Controllers\Api\Payment\Controller\PaymentController; // Correct import
use Illuminate\Support\Facades\Route;

// Define the routes for Payments
Route::prefix('/payment')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [PaymentController::class, 'index']);
    Route::get('/{id}', [PaymentController::class, 'show']);
    Route::post('/', [PaymentController::class, 'store']);
    Route::put('/{id}', [PaymentController::class, 'update']);
    Route::delete('/{id}', [PaymentController::class, 'destroy']);
});
