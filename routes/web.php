<?php

use App\Http\Controllers\AdminPortalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CeoPortalController;
use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MotorCatalogController;
use App\Http\Controllers\UserPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/motors', [MotorCatalogController::class, 'index'])->name('motors.index');
Route::get('/motors/{motor}', [MotorCatalogController::class, 'show'])->name('motors.show');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardRedirectController::class)->name('dashboard');

    Route::prefix('user')->name('user.')->middleware('role:user')->group(function (): void {
        Route::get('/dashboard', [UserPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [UserPortalController::class, 'profile'])->name('profile');
        Route::get('/pengajuan', [UserPortalController::class, 'pengajuanIndex'])->name('pengajuan.index');
        Route::get('/kredit', [UserPortalController::class, 'kreditIndex'])->name('kredit.index');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function (): void {
        Route::get('/dashboard', [AdminPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/pengajuan', [AdminPortalController::class, 'pengajuanIndex'])->name('pengajuan.index');
        Route::get('/kredit', [AdminPortalController::class, 'kreditIndex'])->name('kredit.index');
        Route::get('/pengiriman', [AdminPortalController::class, 'pengirimanIndex'])->name('pengiriman.index');
    });

    Route::prefix('ceo')->name('ceo.')->middleware('role:ceo')->group(function (): void {
        Route::get('/dashboard', [CeoPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [CeoPortalController::class, 'usersIndex'])->name('users.index');
        Route::get('/transaksi', [CeoPortalController::class, 'transaksiIndex'])->name('transaksi.index');
    });
});
