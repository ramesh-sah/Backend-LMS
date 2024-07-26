


<?php

use App\Http\Controllers\Api\AdminUser\Controller\AdminController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Publishers
Route::prefix('/admin')->group(function () {
    Route::post('/register', [AdminController::class, 'registerAdmin'])->name('registerAdmin');
    Route::post('/login', [AdminController::class, 'loginAdmin'])->name('loginAdmin');

    Route::middleware(['auth:admin'])->group(function () {
        Route::post('/logout', [AdminController::class, 'logoutAdmin'])->name('logoutAdmin');
        Route::get('/', [AdminController::class, 'getAllAdmin'])->name('user.getAllAdmin');
        Route::get('/{admin_id}', [AdminController::class, 'getAdmin'])->name('user.getAdmin');
        Route::put('/{admin_id}', [AdminController::class, 'updateAdmin'])->name('user.updateAdmin');
        Route::delete('/{admin_id}', [AdminController::class, 'destroyAdmin'])->name('user.destroyAdmin');
    });

    

});
