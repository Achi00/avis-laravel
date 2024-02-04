<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);

Route::patch('/users/{id}', [UserController::class, 'updateUserValue']);