<?php

use App\Http\Controllers\Api\Issue\Controller\IssueController;
use Illuminate\Support\Facades\Route;

// Define the routes for Issue
Route::prefix('/admin/issue')->middleware(['auth:admin'])->group(function () {
    Route::get('/getAllIssue', [IssueController::class, 'getAllIssue']);
    Route::get('/getUserAllIssue/{member_id}', [IssueController::class, 'getSpecificUserAllIssue']);
    Route::get('/{issue_id}', [IssueController::class, 'getIssue']);
    Route::post('/', [IssueController::class, 'postIssue']);
    Route::post('/renew', [IssueController::class, 'renewIssue']);
    Route::put('/{issue_id}', [IssueController::class, 'updateIssue']);
    Route::delete('/{issue_id}', [IssueController::class, 'destroyIssue']);
});

Route::prefix('/employee/issue')->middleware(['auth:employee'])->group(function () {
    Route::get('/getAllIssue', [IssueController::class, 'getAllIssue']);
    Route::get('/getUserAllIssue/{member_id}', [IssueController::class, 'getSpecificUserAllIssue']);
    Route::get('/{issue_id}', [IssueController::class, 'getIssue']);
    Route::post('/', [IssueController::class, 'postIssue']);
    Route::put('/{issue_id}', [IssueController::class, 'updateIssue']);
    Route::delete('/{issue_id}', [IssueController::class, 'destroyIssue']);
});

Route::prefix('/member/issue')->middleware(['auth:member'])->group(function () {
    Route::get('/getUserAllIssue/{member_id}', [IssueController::class, 'getSpecificUserAllIssue']);
    Route::put('/{issue_id}', [IssueController::class, 'renewRequest']);
});
