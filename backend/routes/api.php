<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\ContractorController;
use App\Http\Controllers\Api\ContractController;
use App\Http\Middleware\SuperAdminMiddleware;


// ============================================================
// AUTHENTICATION
// ============================================================

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);


// ============================================================
// SUPER ADMIN ROUTES
// ============================================================

Route::middleware(['auth:sanctum', SuperAdminMiddleware::class])->group(function () {

    // ========================================================
    // EMPLOYEES
    // ========================================================

    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);


    // ========================================================
    // CONTRACTORS
    // ========================================================

    Route::get('/contractors', [ContractorController::class, 'index']);
    Route::post('/contractors', [ContractorController::class, 'store']);
    Route::get('/contractors/{id}', [ContractorController::class, 'show']);
    Route::put('/contractors/{id}', [ContractorController::class, 'update']);
    Route::delete('/contractors/{id}', [ContractorController::class, 'destroy']);


    // ========================================================
    // CONTRACTS
    // ========================================================

    Route::get('/contracts', [ContractController::class, 'index']);
    Route::post('/contracts', [ContractController::class, 'store']);
    Route::get('/contracts/{id}', [ContractController::class, 'show']);
    Route::put('/contracts/{id}', [ContractController::class, 'update']);
    Route::delete('/contracts/{id}', [ContractController::class, 'destroy']);

});
