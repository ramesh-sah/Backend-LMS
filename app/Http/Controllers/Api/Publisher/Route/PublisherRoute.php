<?php

use App\Http\Controllers\Api\Publisher\Controller\PublishersController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Publishers
Route::prefix('/admin/publishers')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllPublisher', [PublishersController::class, 'getAllPublisher']);
    Route::get('/{publisher_id}', [PublishersController::class, 'getPublisher']);
    Route::post('/', [PublishersController::class, 'postPublisher']);
    Route::put('/{publisher_id}', [PublishersController::class, 'updatePublisher']);
    Route::delete('/{publisher_id}', [PublishersController::class, 'destroyPublisher']);
});

Route::prefix('/employee/publishers')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllPublisher', [PublishersController::class, 'getAllPublisher']);
    Route::get('/{publisher_id}', [PublishersController::class, 'getPublisher']);
    Route::post('/', [PublishersController::class, 'postPublisher']);
    Route::put('/{publisher_id}', [PublishersController::class, 'updatePublisher']);
    Route::delete('/{publisher_id}', [PublishersController::class, 'destroyPublisher']);
});
