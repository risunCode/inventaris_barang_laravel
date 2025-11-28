<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        \App\Models\Commodity::observe(\App\Observers\CommodityObserver::class);
        
        // Define role-based gates for permissions
        Gate::define('users.view', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('users.manage', function ($user) {
            return $user->is_active && $user->role === 'admin';
        });

        Gate::define('commodities.view', function ($user) {
            return $user->is_active;
        });

        Gate::define('commodities.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('commodities.edit', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('categories.view', function ($user) {
            return $user->is_active;
        });

        Gate::define('locations.view', function ($user) {
            return $user->is_active;
        });

        Gate::define('transfers.view', function ($user) {
            return $user->is_active;
        });

        Gate::define('transfers.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('maintenance.view', function ($user) {
            return $user->is_active;
        });

        Gate::define('maintenance.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('disposals.view', function ($user) {
            return $user->is_active;
        });

        Gate::define('reports.view', function ($user) {
            return $user->is_active;
        });

        // Additional missing permissions
        Gate::define('users.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('users.edit', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('users.delete', function ($user) {
            return $user->is_active && $user->role === 'admin';
        });

        Gate::define('commodities.delete', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('commodities.export', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('categories.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('categories.edit', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('categories.delete', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('locations.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('locations.edit', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('locations.delete', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('transfers.approve', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('maintenance.edit', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('maintenance.delete', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('disposals.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('disposals.approve', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        // ========================================
        // NEW PERMISSIONS (from security audit)
        // ========================================

        // Import permission for commodities
        Gate::define('commodities.import', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        // Delete permissions for transfers and disposals
        Gate::define('transfers.delete', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        Gate::define('disposals.delete', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff']);
        });

        // Referral codes management (consistent naming)
        Gate::define('referral-codes.manage', function ($user) {
            return $user->is_active && $user->role === 'admin';
        });

        // Referral codes own access (view/edit/delete own codes only)
        Gate::define('referral-codes.own', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff', 'user']);
        });

        // Referral codes create (all active users can create)
        Gate::define('referral-codes.create', function ($user) {
            return $user->is_active && in_array($user->role, ['admin', 'staff', 'user']);
        });

        // Dashboard access (all authenticated active users)
        Gate::define('dashboard.view', function ($user) {
            return $user->is_active;
        });

        // Notifications access (all authenticated active users)
        Gate::define('notifications.view', function ($user) {
            return $user->is_active;
        });
    }
}
