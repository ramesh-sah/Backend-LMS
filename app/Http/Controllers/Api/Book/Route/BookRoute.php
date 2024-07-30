<?php

use App\Http\Controllers\Api\Book\Controller\BookController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Book
Route::prefix('/admin/book')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllBook', [BookController::class, 'getAllBook']);

    //add direct book 
    Route::post('/addBook', [BookController::class, 'addBook']);
    Route::get('/{book_id}', [BookController::class, 'getBook']);
    Route::post('/', [BookController::class, 'postBook']);
    Route::put('/{book_id}', [BookController::class, 'updateBook']);
    Route::delete('/{book_id}', [BookController::class, 'destroyBook']);
});


Route::prefix('/employee/book')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllBook', [BookController::class, 'getAllBook']);

    //add direct book 
    Route::post('/addBook', [BookController::class, 'addBook']);
    Route::get('/{book_id}', [BookController::class, 'getBook']);
    Route::post('/', [BookController::class, 'postBook']);
    Route::put('/{book_id}', [BookController::class, 'updateBook']);
    Route::delete('/{book_id}', [BookController::class, 'destroyBook']);
});
Route::prefix('/member/book')->middleware(['auth:member'])->group(function () {
    Route::get('/getAllBook', [BookController::class, 'getAllBook']);
    Route::get('/{book_id}', [BookController::class, 'getBook']);
    Route::get('/category/{category_id}', [BookController::class, 'getBookByCategory']);
});
