<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.view', only: ['index', 'show']),
            new Middleware('permission:users.create', only: ['create', 'store']),
            new Middleware('permission:users.edit', only: ['edit', 'update']),
            new Middleware('permission:users.delete', only: ['destroy']),
        ];
    }

    /**
     * Tampilkan daftar pengguna.
     */
    public function index(Request $request): View
    {
        $query = User::with(['roles', 'referrer']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();
        $roles = Role::orderBy('name')->get();

        // Stats
        $adminCount = User::role(['super-admin', 'admin'])->count();
        $canAddAdmin = $adminCount < 3;

        return view('users.index', compact('users', 'roles', 'adminCount', 'canAddAdmin'));
    }

    /**
     * Tampilkan form tambah pengguna.
     */
    public function create(): View
    {
        $roles = Role::orderBy('name')->get();
        $securityQuestions = config('security_questions.questions');

        return view('users.create', compact('roles', 'securityQuestions'));
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        // Simplified validation for modal
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'exists:roles,name'],
            'referral_code' => ['nullable', 'string', 'exists:referral_codes,code'],
            'is_active' => ['boolean'],
        ]);

        // Cek limit admin (super-admin + admin max 3)
        if (in_array($validated['role'], ['super-admin', 'admin']) && !User::canAddAdmin()) {
            $errorMsg = 'Jumlah Super Admin dan Admin sudah mencapai batas maksimal (3 orang).';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return back()->withErrors(['role' => $errorMsg])->withInput();
        }

        // Handle referral code
        $referrerId = null;
        if (!empty($validated['referral_code'])) {
            $referralCode = \App\Models\ReferralCode::where('code', $validated['referral_code'])->first();
            if ($referralCode && $referralCode->isValid()) {
                $referrerId = $referralCode->created_by;
                $referralCode->incrementUsage();
            } else {
                $errorMsg = 'Kode referral tidak valid atau sudah tidak aktif.';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withErrors(['referral_code' => $errorMsg])->withInput();
            }
        }

        $userData = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
            'is_active' => $request->boolean('is_active', true),
            'referred_by' => $referrerId,
            'security_setup_completed' => false, // User harus setup security saat login pertama
        ];

        $user = User::create($userData);
        $user->assignRole($validated['role']);

        ActivityLog::log('created', "Membuat pengguna: {$user->name}", $user);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil ditambahkan. Kode referral: ' . $user->referral_code
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan. Kode referral: ' . $user->referral_code);
    }

    /**
     * Tampilkan detail pengguna.
     */
    public function show(User $user): View
    {
        $user->load(['roles', 'referrer', 'referrals']);
        $activities = ActivityLog::where('user_id', $user->id)
            ->latest()
            ->limit(20)
            ->get();

        return view('users.show', compact('user', 'activities'));
    }

    /**
     * Tampilkan form edit pengguna.
     */
    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();
        $securityQuestions = config('security_questions.questions');

        return view('users.edit', compact('user', 'roles', 'securityQuestions'));
    }

    /**
     * Update pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'string', 'max:20'],
            'role' => ['required', 'exists:roles,name'],
            'is_active' => ['boolean'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        // Cek limit admin jika upgrade ke admin/super-admin
        $currentRole = $user->roles->first()?->name;
        $newRole = $validated['role'];
        $isUpgradeToAdmin = in_array($newRole, ['super-admin', 'admin']) && !in_array($currentRole, ['super-admin', 'admin']);
        
        if ($isUpgradeToAdmin && !User::canAddAdmin()) {
            $errorMsg = 'Jumlah Super Admin dan Admin sudah mencapai batas maksimal (3 orang).';
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return back()->withErrors(['role' => $errorMsg])->withInput();
        }

        $userData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'is_active' => $request->boolean('is_active', true),
        ];

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        // Handle avatar
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $userData['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $oldValues = $user->toArray();
        $user->update($userData);

        // Update role
        $user->syncRoles([$validated['role']]);

        ActivityLog::log('updated', "Mengubah pengguna: {$user->name}", $user, $oldValues, $user->fresh()->toArray());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pengguna berhasil diperbarui.']);
        }

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Hapus pengguna.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // Tidak bisa hapus super admin terakhir
        if ($user->hasRole('super-admin') && User::role('super-admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus super admin terakhir.');
        }

        $userName = $user->name;

        // Hapus avatar
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        ActivityLog::log('deleted', "Menghapus pengguna: {$userName}");

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
