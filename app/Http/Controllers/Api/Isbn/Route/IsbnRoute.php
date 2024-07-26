<?php

use App\Http\Controllers\Api\Isbn\Controller\IsbnController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Isbn
Route::prefix('/admin/isbn')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllIsbn', [IsbnController::class, 'getAllIsbn']);
    Route::get('/{isbn_id}', [IsbnController::class, 'getIsbn']);
    Route::post('/', [IsbnController::class, 'postIsbn']);
    Route::put('/{isbn_id}', [IsbnController::class, 'updateIsbn']);
    Route::delete('/{isbn_id}', [IsbnController::class, 'destroyIsbn']);
});

Route::prefix('/employee/isbn')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllIsbn', [IsbnController::class, 'getAllIsbn']);
    Route::get('/{isbn_id}', [IsbnController::class, 'getIsbn']);
    Route::post('/', [IsbnController::class, 'postIsbn']);
    Route::put('/{isbn_id}', [IsbnController::class, 'updateIsbn']);
    Route::delete('/{isbn_id}', [IsbnController::class, 'destroyIsbn']);
});