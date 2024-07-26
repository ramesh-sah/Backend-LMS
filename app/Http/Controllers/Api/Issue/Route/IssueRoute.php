<?php

use App\Http\Controllers\Api\Issue\Controller\IssueController;
use Illuminate\Support\Facades\Route;

// Define the routes for Issue
Route::prefix('/issue')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [IssueController::class, 'index']);
    Route::get('/{id}', [IssueController::class, 'show']);
    Route::post('/', [IssueController::class, 'store']);
    Route::put('/{id}', [IssueController::class, 'update']);
    Route::delete('/{id}', [IssueController::class, 'destroy']);
});
