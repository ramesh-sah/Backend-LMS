<?php

use App\Http\Controllers\Api\Author\Controller\AuthorController;
use Illuminate\Support\Facades\Route;

// Define the routes for Authors
Route::prefix('/admin/author')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllAuthor', [AuthorController::class, 'getAllAuthor']);
    Route::get('/{author_id}', [AuthorController::class, 'getAuthor']);
    Route::post('/', [AuthorController::class, 'postAuthor']);
    Route::put('/{author_id}', [AuthorController::class, 'updateAuthor']);
    Route::delete('/{author_id}', [AuthorController::class, 'destroyAuthor']);
});

Route::prefix('/employee/author')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllAuthor', [AuthorController::class, 'getAllAuthor']);
    Route::get('/{author_id}', [AuthorController::class, 'getAuthor']);
    Route::post('/', [AuthorController::class, 'postAuthor']);
    Route::put('/{author_id}', [AuthorController::class, 'updateAuthor']);
    Route::delete('/{author_id}', [AuthorController::class, 'destroyAuthor']);
});
