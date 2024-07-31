<?php

use App\Http\Controllers\Api\Payment\Controller\PaymentController; // Correct import
use Illuminate\Support\Facades\Route;

// Define the routes for Payments
Route::prefix('/admin/payment')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllPayments', [PaymentController::class, 'getAllPayments']);
    Route::get('/', [PaymentController::class, 'postPayment']);
    Route::put('/getPaymentByMemberId{member_id}', [PaymentController::class, 'getPaymentsByMemberId']);
    Route::delete('/{payment_id}', [PaymentController::class, 'updatePayment']);
    Route::delete('/{destroyPayment}', [PaymentController::class, 'destroyPayment']);
});

Route::prefix('/employee/payment')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllPayments', [PaymentController::class, 'getAllPayments']);
    Route::get('/', [PaymentController::class, 'postPayment']);
    Route::put('/getPaymentByMemberId{member_id}', [PaymentController::class, 'getPaymentsByMemberId']);
    Route::delete('/{payment_id}', [PaymentController::class, 'updatePayment']);
    Route::delete('/{destroyPayment}', [PaymentController::class, 'destroyPayment']);
});

Route::prefix('/employee/payment')->middleware(['auth:employee'])->group(function () {
    Route::put('/getPaymentByMemberId{member_id}', [PaymentController::class, 'getPaymentsByMemberId']);
});
