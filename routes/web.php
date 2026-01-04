<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CashTransactionController;
use App\Http\Controllers\PettyCashController;
use App\Http\Controllers\ReconciliationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login or dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentication Routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Routes (All authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cash Transactions - All roles can view and create
    Route::resource('transactions', CashTransactionController::class);
    Route::get('/transactions-pending', [CashTransactionController::class, 'pending'])->name('transactions.pending');

    // Approval routes - Only admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/transactions/{transaction}/approve', [CashTransactionController::class, 'approve'])->name('transactions.approve');
        Route::post('/transactions/{transaction}/reject', [CashTransactionController::class, 'reject'])->name('transactions.reject');
    });

    // Petty Cash - All roles can view and manage
    Route::get('/petty-cash', [PettyCashController::class, 'index'])->name('petty-cash.index');
    Route::post('/petty-cash/topup', [PettyCashController::class, 'topup'])->name('petty-cash.topup');
    Route::post('/petty-cash/withdraw', [PettyCashController::class, 'withdraw'])->name('petty-cash.withdraw');

    // Reconciliations - Admin only
    Route::middleware('role:admin')->group(function () {
        Route::resource('reconciliations', ReconciliationController::class)->except(['edit', 'update', 'destroy']);
    });

    // Reports - All roles can view
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');

    // User Management - Only admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/activity-logs', [UserController::class, 'activityLogs'])->name('activity-logs');
    });
});
