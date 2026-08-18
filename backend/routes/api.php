<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [
    AuthController::class,
    'login'
]);

Route::post('/register', [
    AuthController::class,
    'register'
]);

Route::post('/forgot-password', [
    AuthController::class,
    'forgotPassword'
]);
