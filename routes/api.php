<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FetchJobsController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/fetch-jobs', [JobController::class, 'index']);

// Protected routes 
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('jobs', JobController::class)->only(['index', 'store']);
    Route::post('/applications', [JobApplicationController::class, 'store']);
    // Route::get('/fetch-jobs', [JobController::class, 'index']);
    Route::get('/jobs/{id}', [JobController::class, 'show']);
    Route::post('/jobs-create', [JobController::class, 'store']);
});
