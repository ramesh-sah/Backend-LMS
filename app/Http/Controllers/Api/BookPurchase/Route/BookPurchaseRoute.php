<?php

use App\Http\Controllers\Api\BookPurchase\Controller\BookPurchaseController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for BookPurchase
Route::prefix('admin/bookPurchase')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllBookPurchase', [BookPurchaseController::class, 'getAllBookPurchase']);
    Route::get('/{purchase_id}', [BookPurchaseController::class, 'getBookPurchase']);
    Route::post('/', [BookPurchaseController::class, 'postBookPurchase']);
    Route::put('/{purchase_id}', [BookPurchaseController::class, 'updateBookPurchase']);
    Route::delete('/{purchase_id}', [BookPurchaseController::class, 'destroyBookPurchase']);
});

Route::prefix('employee/bookPurchase')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllBookPurchase', [BookPurchaseController::class, 'getAllBookPurchase']);
    Route::get('/{purchase_id}', [BookPurchaseController::class, 'getBookPurchase']);
    Route::post('/', [BookPurchaseController::class, 'postBookPurchase']);
    Route::put('/{purchase_id}', [BookPurchaseController::class, 'updateBookPurchase']);
    Route::delete('/{purchase_id}', [BookPurchaseController::class, 'destroyBookPurchase']);
});
