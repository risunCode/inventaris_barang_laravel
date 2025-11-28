<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class MultiPermissionMiddleware
{
    /**
     * Handle an incoming request.
     * Supports OR logic with pipe separator: "permission1|permission2"
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return redirect()->route('auth');
        }

        // Split permissions by pipe for OR logic
        $permissions = explode('|', $permission);
        
        // Check if user has ANY of the permissions (OR logic)
        $hasPermission = false;
        foreach ($permissions as $perm) {
            if (Gate::allows(trim($perm))) {
                $hasPermission = true;
                break;
            }
        }

        if (!$hasPermission) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}
