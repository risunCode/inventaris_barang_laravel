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
        Route::post('forgot-password', [PasswordResetController::class, 'store'])->name('password.email'); // Change to store
        Route::post('security-questions', [PasswordResetController::class, 'verifySecurityQuestions'])->name('password.verify'); // No token
        Route::post('reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
    });

    // Validate Referral Code (API) - Rate limited separately
    Route::middleware('throttle:10,1')->get('api/validate-referral', [RegisterController::class, 'validateReferral']);

    // Password Reset Forms (no rate limit needed for GET)
    Route::get('forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::get('security-questions', [PasswordResetController::class, 'showSecurityQuestions'])->name('password.security'); // No token
    Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
});

// Setup Security (WAJIB untuk semua user yang belum setup)
Route::middleware('auth')->group(function () {
    Route::get('security/setup', [RegisterController::class, 'showSetupSecurity'])->name('security.setup');
    Route::post('security/setup', [RegisterController::class, 'storeSetupSecurity'])->name('security.store');
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

    // ========================================
    // MASTER DATA (Barang, Kategori, Lokasi)
    // ========================================
    Route::prefix('master')->group(function () {
        // Barang (Commodities)
        Route::prefix('barang')->group(function () {
            // Preview Item Code (API) - MUST be before resource routes
            Route::get('preview-code', [CommodityController::class, 'previewCode'])->name('commodities.preview-code');
            
            // Export (MUST be before resource routes to avoid conflict)
            Route::get('ekspor', [CommodityController::class, 'export'])->name('commodities.export');
            
            // Debug PDF test
            Route::get('test-pdf', function() {
                $commodities = \App\Models\Commodity::with(['category', 'location'])->limit(5)->get();
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.pdf.inventory', [
                    'commodities' => $commodities,
                    'title' => 'Test PDF Export',
                    'date' => now()->format('d F Y'),
                    'filters' => []
                ]);
                return $pdf->download('test-export.pdf');
            });
            
            Route::resource('/', CommodityController::class)->parameters(['' => 'commodity'])->names([
                'index' => 'commodities.index',
                'create' => 'commodities.create',
                'store' => 'commodities.store',
                'show' => 'commodities.show',
                'edit' => 'commodities.edit',
                'update' => 'commodities.update',
                'destroy' => 'commodities.destroy',
            ]);
        });

        // Kategori (Categories)
        Route::resource('kategori', CategoryController::class)->names([
            'index' => 'categories.index',
            'create' => 'categories.create',
            'store' => 'categories.store',
            'show' => 'categories.show',
            'edit' => 'categories.edit',
            'update' => 'categories.update',
            'destroy' => 'categories.destroy',
        ])->parameters(['kategori' => 'category']);

        // Lokasi (Locations)
        Route::resource('lokasi', LocationController::class)->names([
            'index' => 'locations.index',
            'create' => 'locations.create',
            'store' => 'locations.store',
            'show' => 'locations.show',
            'edit' => 'locations.edit',
            'update' => 'locations.update',
            'destroy' => 'locations.destroy',
        ])->parameters(['lokasi' => 'location']);
    });

    // ========================================
    // TRANSAKSI (Transfer, Maintenance, Penghapusan)
    // ========================================
    Route::prefix('transaksi')->group(function () {
        // Transfer (Mutasi)
        Route::prefix('transfer')->group(function () {
            Route::post('{transfer}/setujui', [TransferController::class, 'approve'])->name('transfers.approve');
            Route::post('{transfer}/tolak', [TransferController::class, 'reject'])->name('transfers.reject');
            Route::post('{transfer}/selesai', [TransferController::class, 'complete'])->name('transfers.complete');
        });
        Route::resource('transfer', TransferController::class)->except(['edit', 'update'])->names([
            'index' => 'transfers.index',
            'create' => 'transfers.create',
            'store' => 'transfers.store',
            'show' => 'transfers.show',
            'destroy' => 'transfers.destroy',
        ]);

        // Maintenance (Pemeliharaan)
        Route::resource('maintenance', MaintenanceController::class)->names([
            'index' => 'maintenance.index',
            'create' => 'maintenance.create',
            'store' => 'maintenance.store',
            'show' => 'maintenance.show',
            'edit' => 'maintenance.edit',
            'update' => 'maintenance.update',
            'destroy' => 'maintenance.destroy',
        ]);

        // Penghapusan (Disposals)
        Route::prefix('penghapusan')->group(function () {
            Route::post('{disposal}/setujui', [DisposalController::class, 'approve'])->name('disposals.approve');
            Route::post('{disposal}/tolak', [DisposalController::class, 'reject'])->name('disposals.reject');
        });
        Route::resource('penghapusan', DisposalController::class)->except(['edit', 'update'])->names([
            'index' => 'disposals.index',
            'create' => 'disposals.create',
            'store' => 'disposals.store',
            'show' => 'disposals.show',
            'destroy' => 'disposals.destroy',
        ])->parameters(['penghapusan' => 'disposal']);
    });

    // ========================================
    // LAPORAN (Reports)
    // ========================================
    Route::prefix('laporan')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('inventaris', [ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('per-kategori', [ReportController::class, 'byCategory'])->name('reports.by-category');
        Route::get('per-lokasi', [ReportController::class, 'byLocation'])->name('reports.by-location');
        Route::get('per-kondisi', [ReportController::class, 'byCondition'])->name('reports.by-condition');
        Route::get('transfer', [ReportController::class, 'transfers'])->name('reports.transfers');
        Route::get('penghapusan', [ReportController::class, 'disposals'])->name('reports.disposals');
        Route::get('maintenance', [ReportController::class, 'maintenance'])->name('reports.maintenance');
        Route::get('kib', [ReportController::class, 'kib'])->name('reports.kib');
    });

    // ========================================
    // ADMIN (Pengguna, Kode Referral)
    // ========================================
    Route::prefix('admin')->group(function () {
        // Transfer Barang
        Route::resource('transfer', TransferController::class)->names([
            'index' => 'transfers.index',
            'create' => 'transfers.create',
            'store' => 'transfers.store',
            'show' => 'transfers.show',
            'edit' => 'transfers.edit',
            'update' => 'transfers.update',
            'destroy' => 'transfers.destroy',
        ]);
        

        // Pengguna (Users)
        Route::resource('pengguna', UserController::class)->names([
            'index' => 'users.index',
            'create' => 'users.create',
            'store' => 'users.store',
            'show' => 'users.show',
            'edit' => 'users.edit',
            'update' => 'users.update',
            'destroy' => 'users.destroy',
        ])->parameters(['pengguna' => 'user']);

        // Kode Referral
        Route::prefix('kode-referral')->group(function () {
            Route::get('/', [ReferralCodeController::class, 'index'])
                 ->middleware('permission:referral-codes.own')
                 ->name('referral-codes.index');
            
            Route::post('/', [ReferralCodeController::class, 'store'])
                 ->middleware('permission:referral-codes.create')
                 ->name('referral-codes.store');
            
            Route::get('generate', [ReferralCodeController::class, 'generate'])
                 ->middleware('permission:referral-codes.create')
                 ->name('referral-codes.generate');
            
            Route::put('{referralCode}', [ReferralCodeController::class, 'update'])
                 ->middleware('permission:referral-codes.own')
                 ->name('referral-codes.update');
                 
            Route::post('{referralCode}/toggle', [ReferralCodeController::class, 'toggle'])
                 ->middleware('permission:referral-codes.own')
                 ->name('referral-codes.toggle');
                 
            Route::delete('{referralCode}', [ReferralCodeController::class, 'destroy'])
                 ->middleware('permission:referral-codes.own')
                 ->name('referral-codes.destroy');
        });
    });

    // About Page
    Route::get('about', fn() => view('about'))->name('about');
});
