<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);

Route::patch('/users/{id}', [UserController::class, 'updateUserValue']);

Route::get('/users-with-value', [UserController::class, 'getUsersWithValue']);

Route::get('/events', [UserController::class, 'events']);

Route::get('/values-count', [UserController::class, 'getValuesCount']);

Route::get('/users-value-and-counts', [UserController::class, 'getUsersWithValueAndCountValues']);

Route::get('/users-cycle', [UserController::class, 'getUsersWithInterestAndCycle']);