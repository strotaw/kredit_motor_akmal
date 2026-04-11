<?php

use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Ceo\DashboardController as CeoDashboardApiController;
use App\Http\Controllers\Api\Public\MotorController;
use App\Http\Controllers\Api\User\DashboardController as UserDashboardApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('web')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth');
});

Route::get('/motors', [MotorController::class, 'index']);
Route::get('/motors/{motor}', [MotorController::class, 'show']);

Route::middleware(['web', 'auth'])->group(function (): void {
    Route::middleware('role:user')->get('/user/dashboard', UserDashboardApiController::class);
    Route::middleware('role:admin')->get('/admin/dashboard', AdminDashboardApiController::class);
    Route::middleware('role:ceo')->get('/ceo/dashboard', CeoDashboardApiController::class);
});
