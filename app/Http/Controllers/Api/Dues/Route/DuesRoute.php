<?php

use App\Http\Controllers\Api\Publisher\Controller\PublishersController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Publishers
Route::prefix('/admin/dues')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllDues', [PublishersController::class, 'getAllDues']);
    Route::get('/{due_id}', [PublishersController::class, 'getSpecificUserAllDues']);
});
Route::prefix('/employee/dues')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllDues', [PublishersController::class, 'getAllDues']);
    Route::get('/{due_id}', [PublishersController::class, 'getSpecificUserAllDues']);
});

Route::prefix('/member/dues')->middleware(['auth:member'])->group(function () {
    Route::get('/{due_id}', [PublishersController::class, 'getSpecificUserAllDues']);
});
