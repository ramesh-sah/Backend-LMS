<?php

use App\Http\Controllers\Api\Membership\Controller\MembershipController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Membership
Route::prefix('/admin/membership')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllMembership', [MembershipController::class, 'getAllMembership']);
    Route::get('/{membership_id}', [MembershipController::class, 'getMembership']);
    Route::post('/', [MembershipController::class, 'postMembership']);
    Route::put('/{membership_id}', [MembershipController::class, 'updateMembership']);
    Route::delete('/{membership_id}', [MembershipController::class, 'destroyMembership']);
});

Route::prefix('/employee/membership')->middleware(['auth:employee'])->group(function () {
    Route::get('/{membership_id}', [MembershipController::class, 'getMembership']);
    Route::get('/getUserMembership/{user_id}', [MembershipController::class, 'getUserMembership']);
});
Route::prefix('/member/membership')->middleware(['auth:member'])->group(function () {
    Route::get('/{membership_id}', [MembershipController::class, 'getMembership']);
    Route::get('/getUserMembership/{user_id}', [MembershipController::class, 'getUserMembership']);
});
