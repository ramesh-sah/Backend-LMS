


<?php

use App\Http\Controllers\Api\Employee\Controller\EmployeeController; // Correct import
use App\Http\Controllers\Api\Employee\Model\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Publishers
Route::prefix('/employee')->group(function () {
    Route::post('/register', [EmployeeController::class, 'registerEmployee'])->name('registerEmployee');
    Route::post('/login', [EmployeeController::class, 'loginEmployee'])->name('loginEmployee');

    Route::middleware(['auth:employee'])->group(function () {
        Route::post('/logout', [EmployeeController::class, 'logoutEmployee'])->name('logoutEmployee');
        Route::get('/{employee_id}', [EmployeeController::class, 'getEmployee'])->name('user.getEmployee');
        Route::put('/{employee_id}', [EmployeeController::class, 'updateEmployee'])->name('user.updateEmployee');
        Route::delete('/{employee_id}', [EmployeeController::class, 'destroyEmployee'])->name('user.destroyEmployee');
    });

    // Add your user CRUD routes here

});



Route::prefix('/admin/employee')->middleware(['auth:admin'])->group(function () {

    Route::post('/register', [EmployeeController::class, 'registerEmployee'])->name('registerEmployee');
    Route::post('/logout', [EmployeeController::class, 'logoutEmployee'])->name('logoutEmployee');
    Route::get('/getAllEmployee', [EmployeeController::class, 'getAllEmployee'])->name('user.getAllEmployee');
    Route::get('/{employee_id}', [EmployeeController::class, 'getEmployee'])->name('user.getEmployee');
    Route::put('/{employee_id}', [EmployeeController::class, 'updateEmployee'])->name('user.updateEmployee');
    Route::delete('/{employee_id}', [EmployeeController::class, 'destroyEmployee'])->name('user.destroyEmployee');
});
