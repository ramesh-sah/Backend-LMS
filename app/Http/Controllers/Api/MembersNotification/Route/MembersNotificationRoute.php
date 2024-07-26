<?php

use App\Http\Controllers\Api\MembersNotification\Controller\MembersNotificationController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for MemebersNotification
Route::prefix('/admin/memberNotification')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllMembersNotification', [MembersNotificationController::class, 'getAllMembersNotification']);
    Route::get('/{member_notification_id}', [MembersNotificationController::class, 'getMembersNotification']);
    Route::post('/', [MembersNotificationController::class, 'postMembersNotification']);
    Route::put('/{member_notification_id}', [MembersNotificationController::class, 'updateMembersNotification']);
    Route::delete('/{member_notification_id}', [MembersNotificationController::class, 'destroyMembersNotification']);
});

Route::prefix('/employee/memberNotification')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllMembersNotification', [MembersNotificationController::class, 'getAllMembersNotification']);
    Route::get('/{member_notification_id}', [MembersNotificationController::class, 'getMembersNotification']);
    Route::post('/', [MembersNotificationController::class, 'postMembersNotification']);
    Route::put('/{member_notification_id}', [MembersNotificationController::class, 'updateMembersNotification']);
    Route::delete('/{member_notification_id}', [MembersNotificationController::class, 'destroyMembersNotification']);
});

Route::prefix('/member/memberNotification')->middleware(['auth:member'])->group(function () {
    Route::get('/getAllMembersNotification', [MembersNotificationController::class, 'getAllMembersNotification']);
    Route::get('/{member_notification_id}', [MembersNotificationController::class, 'getMembersNotification']);
   
});
