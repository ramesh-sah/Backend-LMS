<?php

use App\Http\Controllers\Api\Due\Controller\DueController;
use Illuminate\Support\Facades\Route;

// Define the routes for Publishers
Route::prefix('/dues')->group(function () {
    Route::get('/', [DueController::class, 'index']);
    Route::get('/{id}', [DueController::class, 'show']);
    Route::post('/', [DueController::class, 'store']);
    Route::put('/{id}', [DueController::class, 'update']);
    Route::get('/member/{member_id}', [DueController::class, 'getDueOfMember']); // to get all the due of a member
    Route::get('/member/total/{member_id}', [DueController::class, 'getTotalDue']); // to get total due amount of a member
    Route::delete('/{id}', [DueController::class, 'destroy']);
});
