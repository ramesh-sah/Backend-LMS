<?php

use App\Http\Controllers\Api\Notification\Controller\NotificationController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Notification
Route::prefix('/admin/notification')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllNotification', [NotificationController::class, 'getAllNotification']);
    Route::get('/{notification_id}', [NotificationController::class, 'getNotification']);
    Route::post('/', [NotificationController::class, 'postNotification']);
    Route::put('/{notification_id}', [NotificationController::class, 'updateNotification']);
    Route::delete('/{notification_id}', [NotificationController::class, 'destroyNotification']);
});


Route::prefix('/employee/notification')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllNotification', [NotificationController::class, 'getAllNotification']);
    Route::get('/{notification_id}', [NotificationController::class, 'getNotification']);
    Route::post('/', [NotificationController::class, 'postNotification']);
    Route::put('/{notification_id}', [NotificationController::class, 'updateNotification']);
    Route::delete('/{notification_id}', [NotificationController::class, 'destroyNotification']);
});

