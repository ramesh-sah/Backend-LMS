


<?php

use App\Http\Controllers\Api\Member\Controller\MemberController; // Correct import
use App\Http\Controllers\Api\Member\Model\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Publishers
Route::prefix('/member')->group(function () {
    Route::post('/register', [MemberController::class, 'registerMember'])->name('registerMember');
    Route::post('/login', [MemberController::class, 'loginMember'])->name('loginMember');

    Route::middleware(['auth:member'])->group(function () {
        Route::post('/logout', [MemberController::class, 'logoutMember'])->name('logoutMember');
        Route::get('/{member_id}', [MemberController::class, 'getMember'])->name('user.getMember');
        Route::put('/{member_id}', [MemberController::class, 'updateMember'])->name('user.updateMember');
        Route::delete('/{member_id}', [MemberController::class, 'destroyMember'])->name('user.destroyMember');
    });
});
Route::prefix('/admin/member')->middleware(['auth:admin'])->group(function () {
    Route::post('/register', [MemberController::class, 'registerMember'])->name('registerMember');
    Route::post('/logout', [MemberController::class, 'logoutMember'])->name('logoutMember');
    Route::get('/getAllMember', [MemberController::class, 'getAllMember']);
    Route::get('/{member_id}', [MemberController::class, 'getMember'])->name('user.getMember');
    Route::put('/{member_id}', [MemberController::class, 'updateMember'])->name('user.updateMember');
    Route::delete('/{member_id}', [MemberController::class, 'destroyMember'])->name('user.destroyMember');
});
