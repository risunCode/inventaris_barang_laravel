<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposalController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReferralCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes (Belum Login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/', fn() => redirect()->route('auth'));
    
    // Auth Page (Login & Register)
    Route::get('auth', [AuthenticatedSessionController::class, 'index'])->name('auth');
    Route::get('login', fn() => redirect()->route('auth'))->name('login');
    
    // Rate Limited Auth Actions (5 attempts per minute)
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        Route::post('register', [RegisterController::class, 'store'])->name('register');
        Route::post('forgot-password', [PasswordResetController::class, 'verifyEmail'])->name('password.email');
        Route::post('security-questions/{token}', [PasswordResetController::class, 'verifySecurityQuestions']);
        Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    });

    // Validate Referral Code (API) - Rate limited separately
    Route::middleware('throttle:10,1')->get('api/validate-referral', [RegisterController::class, 'validateReferral']);

    // Password Reset Forms (no rate limit needed for GET)
    Route::get('forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::get('security-questions/{token}', [PasswordResetController::class, 'showSecurityQuestions'])->name('password.security');
    Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
});

// Setup Security (untuk user baru yang belum setup)
Route::middleware('auth')->group(function () {
    Route::get('auth/setup-security', [RegisterController::class, 'showSetupSecurity'])->name('auth.setup-security');
    Route::post('auth/setup-security', [RegisterController::class, 'storeSetupSecurity'])->name('auth.setup-security.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Sudah Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('profile/security', [ProfileController::class, 'updateSecurity'])->name('profile.security');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');

    // Master Data
    Route::prefix('master')->group(function () {
        Route::resource('commodities', CommodityController::class);
        Route::get('commodities-export', [CommodityController::class, 'export'])->name('commodities.export');
        Route::post('commodities-import', [CommodityController::class, 'import'])->name('commodities.import');
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('locations', LocationController::class)->except(['show']);
    });

    // Transaksi
    Route::prefix('transaksi')->group(function () {
        Route::post('transfers/{transfer}/approve', [TransferController::class, 'approve'])->name('transfers.approve');
        Route::post('transfers/{transfer}/reject', [TransferController::class, 'reject'])->name('transfers.reject');
        Route::post('transfers/{transfer}/complete', [TransferController::class, 'complete'])->name('transfers.complete');
        Route::resource('transfers', TransferController::class)->except(['edit', 'update']);
        Route::resource('maintenance', MaintenanceController::class);
        Route::post('disposals/{disposal}/approve', [DisposalController::class, 'approve'])->name('disposals.approve');
        Route::post('disposals/{disposal}/reject', [DisposalController::class, 'reject'])->name('disposals.reject');
        Route::resource('disposals', DisposalController::class)->except(['edit', 'update']);
    });

    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('by-category', [ReportController::class, 'byCategory'])->name('reports.by-category');
        Route::get('by-location', [ReportController::class, 'byLocation'])->name('reports.by-location');
        Route::get('by-condition', [ReportController::class, 'byCondition'])->name('reports.by-condition');
        Route::get('transfers', [ReportController::class, 'transfers'])->name('reports.transfers');
        Route::get('disposals', [ReportController::class, 'disposals'])->name('reports.disposals');
        Route::get('maintenance', [ReportController::class, 'maintenance'])->name('reports.maintenance');
        Route::get('kib', [ReportController::class, 'kib'])->name('reports.kib');
    });

    // Admin
    Route::prefix('admin')->group(function () {
        Route::resource('users', UserController::class);
        
        // Referral Codes
        Route::get('referral-codes', [ReferralCodeController::class, 'index'])->name('referral-codes.index');
        Route::post('referral-codes', [ReferralCodeController::class, 'store'])->name('referral-codes.store');
        Route::put('referral-codes/{referralCode}', [ReferralCodeController::class, 'update'])->name('referral-codes.update');
        Route::post('referral-codes/{referralCode}/toggle', [ReferralCodeController::class, 'toggle'])->name('referral-codes.toggle');
        Route::delete('referral-codes/{referralCode}', [ReferralCodeController::class, 'destroy'])->name('referral-codes.destroy');
        Route::get('referral-codes/generate', [ReferralCodeController::class, 'generate'])->name('referral-codes.generate');
    });

    // About Page
    Route::get('about', fn() => view('about'))->name('about');
});
