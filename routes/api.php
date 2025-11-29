<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Routes here are automatically prefixed with /api
| Used for AJAX/fetch requests that don't need full web middleware stack
|
*/

/*
|--------------------------------------------------------------------------
| Public API Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

// Validate Referral Code - Rate limited 10 requests per minute
Route::middleware('throttle:10,1')->group(function () {
    Route::get('validate-referral', [RegisterController::class, 'validateReferral'])
        ->name('api.validate-referral');
});

/*
|--------------------------------------------------------------------------
| Note: Other API-style routes remain in web.php for compatibility
|--------------------------------------------------------------------------
| - /master/barang/preview-code → commodities.preview-code
| - /master/barang/ekspor → commodities.export
|
| These use Laravel's route() helper in Blade templates
*/
