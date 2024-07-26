<?php

use App\Http\Controllers\Api\CoverImage\Controller\CoverImageController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for CoverImage
Route::prefix('/admin/coverImage')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllCoverImage', [CoverImageController::class, 'getAllCoverImage']);
    Route::get('/{image_id}', [CoverImageController::class, 'getCoverImage']);
    Route::post('/', [CoverImageController::class, 'postCoverImage']);
    Route::put('/{image_id}', [CoverImageController::class, 'updateCoverImage']);
    Route::delete('/{image_id}', [CoverImageController::class, 'destroyCoverImage']);
});

Route::prefix('/employee/coverImage')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllCoverImage', [CoverImageController::class, 'getAllCoverImage']);
    Route::get('/{image_id}', [CoverImageController::class, 'getCoverImage']);
    Route::post('/', [CoverImageController::class, 'postCoverImage']);
    Route::put('/{image_id}', [CoverImageController::class, 'updateCoverImage']);
    Route::delete('/{image_id}', [CoverImageController::class, 'destroyCoverImage']);
});