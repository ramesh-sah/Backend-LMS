<?php

use App\Http\Controllers\Api\Publisher\Controller\PublishersController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Publishers
Route::prefix('/publishers')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [PublishersController::class, 'index']);
    Route::get('/{id}', [PublishersController::class, 'show']);
    Route::post('/', [PublishersController::class, 'store']);
    Route::put('/{id}', [PublishersController::class, 'update']);
    Route::delete('/{id}', [PublishersController::class, 'destroy']);
});