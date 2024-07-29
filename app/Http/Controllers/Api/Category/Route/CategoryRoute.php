<?php

use App\Http\Controllers\Api\Category\Controller\CategoryController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Category
Route::prefix('/admin/category')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllCategory', [CategoryController::class, 'getAllCategory']);
    Route::get('/{category_id}', [CategoryController::class, 'getCategory']);
    Route::post('/', [CategoryController::class, 'postCategory']);
    Route::put('/{category_id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/{category_id}', [CategoryController::class, 'destroyCategory']);
});


Route::prefix('/employee/category')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllCategory', [CategoryController::class, 'getAllCategory']);
    Route::get('/{category_id}', [CategoryController::class, 'getCategory']);
    Route::post('/', [CategoryController::class, 'postCategory']);
    Route::put('/{category_id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/{category_id}', [CategoryController::class, 'destroyCategory']);
});

Route::prefix('/member/category')->middleware(['auth:member'])->group(function () {
    Route::get('/getAllCategory', [CategoryController::class, 'getAllCategory']);
});
