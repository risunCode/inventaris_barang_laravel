<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommodityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\DisposalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - SIBARANG Inventory System
|--------------------------------------------------------------------------
|
| Routes here are automatically prefixed with /api
| Used for AJAX/fetch requests and mobile app integration
|
| Base URL: /api/v1/...
|
*/

/*
|--------------------------------------------------------------------------
| Public API Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware('throttle:10,1')->group(function () {
    // Validate Referral Code
    Route::get('validate-referral', [RegisterController::class, 'validateReferral'])
        ->name('api.validate-referral');
    
    // Health check & public stats (for testing)
    Route::get('health', fn() => response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => config('app.version', '0.0.3-semi-stable'),
    ]))->name('api.health');
    
    Route::get('stats/public', fn() => response()->json([
        'total_commodities' => \App\Models\Commodity::count(),
        'total_categories' => \App\Models\Category::count(),
        'total_locations' => \App\Models\Location::count(),
    ]))->name('api.stats.public');
});

/*
|--------------------------------------------------------------------------
| Authenticated API Routes
|--------------------------------------------------------------------------
*/

// Use 'auth:sanctum' for mobile apps, 'auth' for web session-based auth
Route::middleware(['auth', 'throttle:60,1'])->prefix('v1')->group(function () {
    
    // ========================================
    // USER & AUTH
    // ========================================
    Route::get('user', fn(Request $request) => $request->user())
        ->name('api.user');
    
    // ========================================
    // DASHBOARD & STATISTICS
    // ========================================
    Route::prefix('dashboard')->group(function () {
        // Get all dashboard stats
        Route::get('stats', function () {
            return response()->json([
                'total_commodities' => \App\Models\Commodity::count(),
                'total_categories' => \App\Models\Category::count(),
                'total_locations' => \App\Models\Location::count(),
                'total_value' => \App\Models\Commodity::sum('purchase_price'),
                'pending_transfers' => \App\Models\Transfer::where('status', 'pending')->count(),
                'pending_disposals' => \App\Models\Disposal::where('status', 'pending')->count(),
            ]);
        })->name('api.dashboard.stats');
        
        // Condition statistics for charts
        Route::get('conditions', function () {
            return response()->json([
                'baik' => \App\Models\Commodity::where('condition', 'baik')->count(),
                'rusak_ringan' => \App\Models\Commodity::where('condition', 'rusak_ringan')->count(),
                'rusak_berat' => \App\Models\Commodity::where('condition', 'rusak_berat')->count(),
            ]);
        })->name('api.dashboard.conditions');
        
        // Commodities by category for charts
        Route::get('by-category', function () {
            return response()->json(
                \App\Models\Category::withCount('commodities')
                    ->orderByDesc('commodities_count')
                    ->limit(10)
                    ->get(['id', 'name', 'commodities_count'])
            );
        })->name('api.dashboard.by-category');
        
        // Commodities by location for charts
        Route::get('by-location', function () {
            return response()->json(
                \App\Models\Location::withCount('commodities')
                    ->orderByDesc('commodities_count')
                    ->limit(10)
                    ->get(['id', 'name', 'commodities_count'])
            );
        })->name('api.dashboard.by-location');
    });
    
    // ========================================
    // COMMODITIES
    // ========================================
    Route::prefix('commodities')->group(function () {
        // List all commodities (paginated)
        Route::get('/', function (Request $request) {
            $query = \App\Models\Commodity::with(['category', 'location']);
            
            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('item_code', 'like', "%{$search}%")
                      ->orWhere('brand', 'like', "%{$search}%");
                });
            }
            
            // Filter by category
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            
            // Filter by location
            if ($request->filled('location_id')) {
                $query->where('location_id', $request->location_id);
            }
            
            // Filter by condition
            if ($request->filled('condition')) {
                $query->where('condition', $request->condition);
            }
            
            return response()->json(
                $query->orderBy('name')->paginate($request->per_page ?? 15)
            );
        })->name('api.commodities.index');
        
        // Get single commodity
        Route::get('{commodity}', function (\App\Models\Commodity $commodity) {
            return response()->json(
                $commodity->load(['category', 'location', 'images', 'creator', 'maintenances'])
            );
        })->name('api.commodities.show');
        
        // Preview item code
        Route::get('code/preview', [CommodityController::class, 'previewCode'])
            ->name('api.commodities.preview-code');
        
        // Search commodities (lightweight)
        Route::get('search/quick', function (Request $request) {
            $search = $request->q;
            return response()->json(
                \App\Models\Commodity::where('name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%")
                    ->limit(10)
                    ->get(['id', 'item_code', 'name', 'condition'])
            );
        })->name('api.commodities.search');
    });
    
    // ========================================
    // CATEGORIES
    // ========================================
    Route::prefix('categories')->group(function () {
        // List all categories
        Route::get('/', function () {
            return response()->json(
                \App\Models\Category::withCount('commodities')
                    ->orderBy('name')
                    ->get()
            );
        })->name('api.categories.index');
        
        // Get single category with commodities
        Route::get('{category}', function (\App\Models\Category $category) {
            return response()->json(
                $category->load('commodities')
            );
        })->name('api.categories.show');
    });
    
    // ========================================
    // LOCATIONS
    // ========================================
    Route::prefix('locations')->group(function () {
        // List all locations
        Route::get('/', function () {
            return response()->json(
                \App\Models\Location::withCount('commodities')
                    ->orderBy('name')
                    ->get()
            );
        })->name('api.locations.index');
        
        // Get single location with commodities
        Route::get('{location}', function (\App\Models\Location $location) {
            return response()->json(
                $location->load('commodities')
            );
        })->name('api.locations.show');
    });
    
    // ========================================
    // TRANSFERS
    // ========================================
    Route::prefix('transfers')->group(function () {
        // List transfers
        Route::get('/', function (Request $request) {
            $query = \App\Models\Transfer::with(['commodity', 'fromLocation', 'toLocation', 'requester']);
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            return response()->json(
                $query->latest()->paginate($request->per_page ?? 15)
            );
        })->name('api.transfers.index');
        
        // Get pending count
        Route::get('pending/count', function () {
            return response()->json([
                'count' => \App\Models\Transfer::where('status', 'pending')->count()
            ]);
        })->name('api.transfers.pending-count');
        
        // Get single transfer
        Route::get('{transfer}', function (\App\Models\Transfer $transfer) {
            return response()->json(
                $transfer->load(['commodity', 'fromLocation', 'toLocation', 'requester', 'approver'])
            );
        })->name('api.transfers.show');
    });
    
    // ========================================
    // MAINTENANCE
    // ========================================
    Route::prefix('maintenance')->group(function () {
        // List maintenance logs
        Route::get('/', function (Request $request) {
            return response()->json(
                \App\Models\Maintenance::with(['commodity', 'creator'])
                    ->latest('maintenance_date')
                    ->paginate($request->per_page ?? 15)
            );
        })->name('api.maintenance.index');
        
        // Get upcoming maintenance
        Route::get('upcoming', function () {
            return response()->json(
                \App\Models\Maintenance::with('commodity')
                    ->whereNotNull('next_maintenance_date')
                    ->where('next_maintenance_date', '>=', now())
                    ->where('next_maintenance_date', '<=', now()->addDays(30))
                    ->orderBy('next_maintenance_date')
                    ->get()
            );
        })->name('api.maintenance.upcoming');
        
        // Get overdue maintenance
        Route::get('overdue', function () {
            return response()->json(
                \App\Models\Maintenance::with('commodity')
                    ->whereNotNull('next_maintenance_date')
                    ->where('next_maintenance_date', '<', now())
                    ->orderBy('next_maintenance_date')
                    ->get()
            );
        })->name('api.maintenance.overdue');
    });
    
    // ========================================
    // DISPOSALS
    // ========================================
    Route::prefix('disposals')->group(function () {
        // List disposals
        Route::get('/', function (Request $request) {
            $query = \App\Models\Disposal::with(['commodity', 'requester']);
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            return response()->json(
                $query->latest()->paginate($request->per_page ?? 15)
            );
        })->name('api.disposals.index');
        
        // Get pending count
        Route::get('pending/count', function () {
            return response()->json([
                'count' => \App\Models\Disposal::where('status', 'pending')->count()
            ]);
        })->name('api.disposals.pending-count');
    });
    
    // ========================================
    // NOTIFICATIONS
    // ========================================
    Route::prefix('notifications')->group(function () {
        // Get unread count
        Route::get('unread/count', function (Request $request) {
            return response()->json([
                'count' => $request->user()->unreadNotifications()->count()
            ]);
        })->name('api.notifications.unread-count');
        
        // Get recent notifications
        Route::get('recent', function (Request $request) {
            return response()->json(
                $request->user()->notifications()->limit(10)->get()
            );
        })->name('api.notifications.recent');
        
        // Mark notification as read
        Route::post('{id}/read', function (Request $request, string $id) {
            $notification = $request->user()->notifications()->findOrFail($id);
            $notification->markAsRead();
            return response()->json(['success' => true]);
        })->name('api.notifications.read');
        
        // Mark all as read
        Route::post('read-all', function (Request $request) {
            $request->user()->unreadNotifications->markAsRead();
            return response()->json(['success' => true]);
        })->name('api.notifications.read-all');
    });
    
    // ========================================
    // SEARCH (Global)
    // ========================================
    Route::get('search', function (Request $request) {
        $query = $request->q;
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }
        
        $commodities = \App\Models\Commodity::where('name', 'like', "%{$query}%")
            ->orWhere('item_code', 'like', "%{$query}%")
            ->limit(5)
            ->get(['id', 'item_code', 'name'])
            ->map(fn($item) => ['type' => 'commodity', 'id' => $item->id, 'text' => "{$item->item_code} - {$item->name}"]);
        
        $categories = \App\Models\Category::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name'])
            ->map(fn($item) => ['type' => 'category', 'id' => $item->id, 'text' => $item->name]);
        
        $locations = \App\Models\Location::where('name', 'like', "%{$query}%")
            ->limit(3)
            ->get(['id', 'name'])
            ->map(fn($item) => ['type' => 'location', 'id' => $item->id, 'text' => $item->name]);
        
        return response()->json([
            'results' => $commodities->concat($categories)->concat($locations)
        ]);
    })->name('api.search');
    
    // ========================================
    // ACTIVITY LOG
    // ========================================
    Route::get('activities/recent', function () {
        return response()->json(
            \App\Models\ActivityLog::with('user')
                ->where('action', '!=', 'login')
                ->latest()
                ->limit(20)
                ->get()
        );
    })->name('api.activities.recent');
});

/*
|--------------------------------------------------------------------------
| Fallback for Web-Based API Routes
|--------------------------------------------------------------------------
| Some routes remain in web.php for Blade template compatibility:
| - /master/barang/preview-code → commodities.preview-code (uses route() helper)
| - /master/barang/ekspor → commodities.export
*/
