<?php

use App\Http\Controllers\Api\Barcode\Controller\BarcodeController; // Correct import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Define the routes for Barcode
Route::prefix('/admin/barcode')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllBarcode', [BarcodeController::class, 'getAllBarcode']);
    Route::get('/{barcode_id}', [BarcodeController::class, 'getBarcode']);
    Route::post('/', [BarcodeController::class, 'postBarcode']);
    Route::put('/{barcode_id}', [BarcodeController::class, 'updateBarcode']);
    Route::delete('/{barcode_id}', [BarcodeController::class, 'destroyBarcode']);
});
Route::prefix('/employee/barcode')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllBarcode', [BarcodeController::class, 'getAllBarcode']);
    Route::get('/{barcode_id}', [BarcodeController::class, 'getBarcode']);
    Route::post('/', [BarcodeController::class, 'postBarcode']);
    Route::put('/{barcode_id}', [BarcodeController::class, 'updateBarcode']);
    Route::delete('/{barcode_id}', [BarcodeController::class, 'destroyBarcode']);
});