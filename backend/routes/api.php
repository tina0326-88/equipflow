<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // Equipment
    Route::apiResource('equipment', EquipmentController::class);

    // Repairs
    Route::get('/repairs', [RepairController::class, 'index']);
    Route::post('/repairs', [RepairController::class, 'store']);
    Route::get('/repairs/{id}', [RepairController::class, 'show']);
    Route::put('/repairs/{id}', [RepairController::class, 'update']);
    Route::patch('/repairs/{id}/status', [RepairController::class, 'updateStatus']);
    Route::delete('/repairs/{id}', [RepairController::class, 'destroy']);

    // Repair Logs
    Route::get('/repairs/{id}/logs', [RepairController::class, 'logs']);
    Route::post('/repairs/{id}/logs', [RepairController::class, 'addLog']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
    Route::get('/dashboard/latest-repairs', [DashboardController::class, 'latestRepairs']);
});
