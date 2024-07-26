<?php

use App\Http\Controllers\Api\BookOnline\Controller\BookOnlineController; // Correct import
use Illuminate\Support\Facades\Route;

// Define the routes for BookOnlines
Route::prefix('/admin/bookOnline')->middleware('auth:admin')->group(function () {
    Route::get('getAllBookOnline', [BookOnlineController::class, 'getAllBookOnline']);
    Route::get('/{online_id}', [BookOnlineController::class, 'getBookOnline']);
    Route::post('', [BookOnlineController::class, 'postBookOnline']);
    Route::put('/{online_id}', [BookOnlineController::class, 'updateBookOnline']);
    Route::delete('/{online_id}', [BookOnlineController::class, 'destroyBookOnline']);
});


Route::prefix('/employee/bookOnline')->middleware('auth:employee')->group(function () {
    Route::get('/getAllBookOnline', [BookOnlineController::class, 'getAllBookOnline']);
    Route::get('/{online_id}', [BookOnlineController::class, 'getBookOnline']);
    Route::post('/', [BookOnlineController::class, 'postBookOnline']);
    Route::put('/{online_id}', [BookOnlineController::class, 'updateBookOnline']);
    Route::delete('/{online_id}', [BookOnlineController::class, 'destroyBookOnline']);
});
