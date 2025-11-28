<?php

namespace App\Http\Controllers;

use App\Models\ReferralCode;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class ReferralCodeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('multi-permission:referral-codes.manage|referral-codes.own'),
        ];
    }

    /**
     * Display a listing of referral codes.
     */
    public function index(Request $request)
    {
        // Admin sees all codes, users see own codes only (via global scope)
        $query = ReferralCode::with('creator')
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where(function ($q) {
                          $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                      });
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        $perPage = min($request->get('per_page', 10), 100); // Max 100 per page
        $referralCodes = $query->paginate($perPage);

        // Stats based on user role
        $statsQuery = auth()->user()->role === 'admin' 
            ? ReferralCode::query()
            : ReferralCode::where('created_by', auth()->id());
            
        $stats = [
            'total' => $statsQuery->count(),
            'active' => $statsQuery->where('is_active', true)
                ->where(function ($q) {
                    $q->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
                })->count(),
            'total_used' => $statsQuery->sum('used_count'),
        ];

        return view('referral-codes.index', compact('referralCodes', 'stats'));
    }

    /**
     * Store a newly created referral code.
     */
    public function store(Request $request): JsonResponse
    {
        // Security: Check create permission
        if (!Gate::allows('referral-codes.create')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk membuat kode referral.',
            ], 403);
        }

        $validated = $request->validate([
            'code' => 'nullable|string|max:20|unique:referral_codes,code',
            'description' => 'nullable|string|max:255',
            'max_uses' => 'nullable|integer|min:1|max:100', // Limit max uses for security
            'expires_at' => 'nullable|date|after:now|before:+5 years',
            'role' => 'nullable|string|in:admin,staff,user',
        ]);

        // Security: Prevent role escalation - force same role or lower
        $userRole = auth()->user()->role;
        $allowedRoles = match($userRole) {
            'admin' => ['admin', 'staff', 'user'],
            'staff' => ['staff', 'user'],
            'user' => ['user'],
            default => ['user']
        };
        
        $targetRole = $validated['role'] ?? 'user';
        if (!in_array($targetRole, $allowedRoles)) {
            $targetRole = end($allowedRoles); // Force to lowest allowed role
        }

        $referralCode = ReferralCode::create([
            'code' => $validated['code'] ?? null,
            'description' => $validated['description'] ?? null,
            'max_uses' => $validated['max_uses'] ?? null,
            'expires_at' => $validated['expires_at'] ?? null,
            'role' => $targetRole, // Security: Use validated role
            'created_by' => auth()->id(), // Auto-set for security
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kode referral berhasil dibuat!',
            'data' => $referralCode,
        ]);
    }

    /**
     * Update the specified referral code.
     */
    public function update(Request $request, ReferralCode $referralCode): JsonResponse
    {
        // Security: Check ownership
        if (!$referralCode->canBeManagedBy(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah kode referral ini.',
            ], 403);
        }

        $validated = $request->validate([
            'description' => 'nullable|string|max:255',
            'max_uses' => 'nullable|integer|min:1|max:100',
            'expires_at' => 'nullable|date|after:now|before:+5 years',
            'role' => 'nullable|string|in:admin,staff,user',
            'is_active' => 'boolean',
        ]);

        // Security: Prevent role escalation on update
        if (isset($validated['role'])) {
            $userRole = auth()->user()->role;
            $allowedRoles = match($userRole) {
                'admin' => ['admin', 'staff', 'user'],
                'staff' => ['staff', 'user'],
                'user' => ['user'],
                default => ['user']
            };
            
            if (!in_array($validated['role'], $allowedRoles)) {
                unset($validated['role']); // Remove role if not allowed
            }
        }

        $referralCode->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Kode referral berhasil diperbarui!',
            'data' => $referralCode->fresh(),
        ]);
    }

    /**
     * Toggle referral code status.
     */
    public function toggle(ReferralCode $referralCode): JsonResponse
    {
        // Security: Check ownership
        if (!$referralCode->canBeManagedBy(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk mengubah kode referral ini.',
            ], 403);
        }

        $referralCode->update([
            'is_active' => !$referralCode->is_active,
        ]);

        $status = $referralCode->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return response()->json([
            'success' => true,
            'message' => "Kode referral berhasil {$status}!",
            'data' => $referralCode->fresh(),
        ]);
    }

    /**
     * Remove the specified referral code.
     */
    public function destroy(ReferralCode $referralCode): JsonResponse
    {
        // Security: Check ownership
        if (!$referralCode->canBeManagedBy(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus kode referral ini.',
            ], 403);
        }

        $referralCode->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kode referral berhasil dihapus!',
        ]);
    }

    /**
     * Generate a new code (AJAX).
     */
    public function generate(): JsonResponse
    {
        // Security: Check create permission
        if (!Gate::allows('referral-codes.create')) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk membuat kode referral.',
            ], 403);
        }

        return response()->json([
            'code' => ReferralCode::generateUniqueCode(),
        ]);
    }
}
