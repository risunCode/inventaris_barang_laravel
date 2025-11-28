<?php

/**
 * SIBARANG Configuration
 * 
 * Konfigurasi aplikasi Sistem Inventaris Barang
 * Dapat disesuaikan untuk kebutuhan instansi yang berbeda
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Application Info
    |--------------------------------------------------------------------------
    */
    'name' => env('APP_DISPLAY_NAME', 'SIBARANG'),
    'version' => '0.0.7-beta-007',
    'description' => 'Sistem Inventaris Barang',
    'license' => 'GPL-3.0',

    /*
    |--------------------------------------------------------------------------
    | Organization Info
    |--------------------------------------------------------------------------
    | Informasi instansi/organisasi yang menggunakan aplikasi
    */
    'organization' => [
        'name' => env('ORG_NAME', 'Biro Pengadaan Barang dan Jasa'),
        'short_name' => env('ORG_SHORT_NAME', 'BPBJ'),
        'address' => env('ORG_ADDRESS', ''),
        'phone' => env('ORG_PHONE', ''),
        'email' => env('ORG_EMAIL', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Feature Toggles
    |--------------------------------------------------------------------------
    | Aktifkan/nonaktifkan fitur sesuai kebutuhan
    | Untuk instansi kecil, bisa nonaktifkan fitur yang tidak diperlukan
    */
    'features' => [
        // Multi-lokasi: false = single office mode, true = multi building/floor/room
        'multi_location' => env('FEATURE_MULTI_LOCATION', false),
        
        // Transfer barang antar lokasi
        'transfers' => env('FEATURE_TRANSFERS', true),
        
        // Maintenance/perbaikan barang
        'maintenance' => env('FEATURE_MAINTENANCE', true),
        
        // Disposal/penghapusan barang
        'disposals' => env('FEATURE_DISPOSALS', true),
        
        // Activity logging
        'activity_log' => env('FEATURE_ACTIVITY_LOG', true),
        
        // Notifications
        'notifications' => env('FEATURE_NOTIFICATIONS', true),
        
        // Referral code untuk registrasi
        'referral_registration' => env('FEATURE_REFERRAL', true),
        
        // Multiple images per commodity
        'commodity_images' => env('FEATURE_COMMODITY_IMAGES', false),
        
        // Export/Import Excel
        'export_import' => env('FEATURE_EXPORT_IMPORT', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        // Rate limiting untuk auth (attempts per minute)
        'auth_rate_limit' => env('AUTH_RATE_LIMIT', 5),
        
        // Session timeout dalam menit
        'session_timeout' => env('SESSION_TIMEOUT', 120),
        
        // Require security questions
        'require_security_questions' => env('REQUIRE_SECURITY_QUESTIONS', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | UI Settings
    |--------------------------------------------------------------------------
    */
    'ui' => [
        // Default theme: 'light', 'dark', 'system'
        'default_theme' => env('DEFAULT_THEME', 'system'),
        
        // Items per page
        'items_per_page' => env('ITEMS_PER_PAGE', 15),
        
        // Show sidebar by default
        'sidebar_default_open' => env('SIDEBAR_DEFAULT_OPEN', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Condition Labels
    |--------------------------------------------------------------------------
    | Label untuk kondisi barang
    */
    'conditions' => [
        'baik' => 'Baik',
        'rusak_ringan' => 'Rusak Ringan',
        'rusak_berat' => 'Rusak Berat',
    ],

    /*
    |--------------------------------------------------------------------------
    | Acquisition Types
    |--------------------------------------------------------------------------
    | Jenis perolehan barang
    */
    'acquisition_types' => [
        'pembelian' => 'Pembelian',
        'hibah' => 'Hibah',
        'bantuan' => 'Bantuan',
        'produksi' => 'Produksi Sendiri',
        'lainnya' => 'Lainnya',
    ],
];
