<?php

use App\Http\Controllers\Api\DamagedBook\Controller\DamagedBookController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for DamagedBook
Route::prefix('/admin/damagedBook')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllDamagedBook', [DamagedBookController::class, 'getAllDamagedBook']);
    Route::get('/{damaged_book_id}', [DamagedBookController::class, 'getDamagedBook']);
    Route::post('/', [DamagedBookController::class, 'postDamagedBook']);
    Route::put('/{damaged_book_id}', [DamagedBookController::class, 'updateDamagedBook']);
    Route::delete('/{damaged_book_id}', [DamagedBookController::class, 'destroyDamagedBook']);
});

Route::prefix('/employee/damagedBook')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllDamagedBook', [DamagedBookController::class, 'getAllDamagedBook']);
    Route::get('/{damaged_book_id}', [DamagedBookController::class, 'getDamagedBook']);
    Route::post('/', [DamagedBookController::class, 'postDamagedBook']);
    Route::put('/{damaged_book_id}', [DamagedBookController::class, 'updateDamagedBook']);
    Route::delete('/{damaged_book_id}', [DamagedBookController::class, 'destroyDamagedBook']);
});
