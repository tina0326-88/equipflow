<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\RepairController;
use App\Http\Controllers\RepairLogController;
use App\Http\Controllers\DashboardController;

// ==================
// 認證相關
// ==================
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

// ==================
// 需要登入才能使用
// ==================
Route::middleware('auth:sanctum')->group(function () {

    // 登出
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // 設備管理
    Route::get('/equipment',          [EquipmentController::class, 'index']);
    Route::get('/equipment/{id}',     [EquipmentController::class, 'show']);
    Route::post('/equipment',         [EquipmentController::class, 'store']);
    Route::put('/equipment/{id}',     [EquipmentController::class, 'update']);
    Route::delete('/equipment/{id}',  [EquipmentController::class, 'destroy']);

    // 報修管理
    Route::get('/repairs',                    [RepairController::class, 'index']);
    Route::get('/repairs/{id}',               [RepairController::class, 'show']);
    Route::post('/repairs',                   [RepairController::class, 'store']);
    Route::put('/repairs/{id}',               [RepairController::class, 'update']);
    Route::patch('/repairs/{id}/status',      [RepairController::class, 'updateStatus']);
    Route::delete('/repairs/{id}',            [RepairController::class, 'destroy']);

    // 報修紀錄
    Route::get('/repairs/{id}/logs',  [RepairLogController::class, 'index']);
    Route::post('/repairs/{id}/logs', [RepairLogController::class, 'store']);

    // 儀表板
    Route::get('/dashboard/stats',          [DashboardController::class, 'stats']);
    Route::get('/dashboard/latest-repairs', [DashboardController::class, 'latestRepairs']);
});