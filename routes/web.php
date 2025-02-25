<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
Route::get('/', function () {
    return view('welcome');
});
Route::post('jobs/apply', [JobApplicationController::class, 'store']);
