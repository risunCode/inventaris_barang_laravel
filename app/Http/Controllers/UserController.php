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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.manage', only: ['index', 'show']), // Admin only for viewing
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
        // HARDCODE SECURITY CHECK - Admin only!
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini. Silakan gunakan halaman Kode Referral.');
        }

        $query = User::with(['referrer']);

        // ROLE-BASED FILTERING - Security fix
        if (auth()->user()->role === 'user') {
            // Users can only see themselves
            $query->where('id', auth()->id());
        } elseif (auth()->user()->role === 'staff') {
            // Staff can only see users (not admin or other staff)
            $query->where('role', 'user');
        }
        // Admin can see everyone (no filtering)

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('referral_code', 'like', "%{$search}%");
            });
        }

        // Filter by role (only for admin - staff already filtered above)
        if (auth()->user()->role === 'admin' && $request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $perPage = min($request->get('per_page', 15), 100); // Max 100 per page
        $users = $query->orderBy('name')->paginate($perPage)->withQueryString();
        
        // ROLE-BASED OPTIONS - Security fix
        if (auth()->user()->role === 'admin') {
            $roles = [
                'admin' => 'Administrator',
                'staff' => 'Staff',
                'user' => 'User'
            ];
            // Admin can see admin count
            $adminCount = User::where('role', 'admin')->count();
            $canAddAdmin = $adminCount < 3;
        } elseif (auth()->user()->role === 'staff') {
            // Staff can only create users
            $roles = [
                'user' => 'User'
            ];
            $adminCount = 0; // Hidden from staff
            $canAddAdmin = false;
        } else {
            // User shouldn't be here, but just in case
            $roles = [];
            $adminCount = 0;
            $canAddAdmin = false;
        }

        return view('users.index', compact('users', 'roles', 'adminCount', 'canAddAdmin'));
    }

    /**
     * Tampilkan form tambah pengguna.
     */
    public function create(): View
    {
        // ROLE-BASED OPTIONS - Security fix
        if (auth()->user()->role === 'admin') {
            $roles = [
                'admin' => 'Administrator',
                'staff' => 'Staff',
                'user' => 'User'
            ];
        } elseif (auth()->user()->role === 'staff') {
            // Staff can only create users (no role escalation)
            $roles = [
                'user' => 'User'
            ];
        } else {
            // User shouldn't be here
            $roles = [];
        }
        
        $securityQuestions = config('security_questions.questions');

        return view('users.create', compact('roles', 'securityQuestions'));
    }

    /**
     * Simpan pengguna baru.
     */
    public function store(Request $request)
    {
        // ROLE-BASED VALIDATION - Security fix
        if (auth()->user()->role === 'staff') {
            // Staff can only create users
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
                'role' => ['required', 'in:user'], // STAFF CAN ONLY CREATE USERS
                'referral_code' => ['nullable', 'string', 'exists:referral_codes,code'],
                'is_active' => ['boolean'],
                'birth_date' => ['required', 'date', 'before:today'],
                'security_question_1' => ['required', 'in:0,1,2,3,4,5,6,7,8,9,10'],
                'custom_security_question' => ['required_if:security_question_1,0', 'string', 'max:255'],
                'security_answer_1' => ['required', 'string', 'min:3'],
            ]);
        } elseif (auth()->user()->role === 'admin') {
            // Admin can create all roles (with limits)
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'confirmed', Password::defaults()],
                'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
                'role' => ['required', 'in:admin,staff,user'],
                'referral_code' => ['nullable', 'string', 'exists:referral_codes,code'],
                'is_active' => ['boolean'],
                'birth_date' => ['required', 'date', 'before:today'],
                'security_question_1' => ['required', 'in:0,1,2,3,4,5,6,7,8,9,10'],
                'custom_security_question' => ['required_if:security_question_1,0', 'string', 'max:255'],
                'security_answer_1' => ['required', 'string', 'min:3'],
            ]);
        } else {
            // Users shouldn't be creating users
            abort(403, 'Anda tidak memiliki izin untuk membuat pengguna.');
        }

        // Cek limit admin (max 3) - only for admin
        if (auth()->user()->role === 'admin' && $validated['role'] === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 3) {
                $errorMsg = 'Jumlah Admin sudah mencapai batas maksimal (3 orang).';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withErrors(['role' => $errorMsg])->withInput();
            }
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

        // Handle security question
        $questionValue = (int) $validated['security_question_1'];
        
        $userData = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
            'referred_by' => $referrerId,
            'birth_date' => $validated['birth_date'],
            'security_question_1' => $questionValue, // Integer (0 for custom)
            'security_answer_1' => Hash::make(strtolower(trim($validated['security_answer_1']))),
            'custom_security_question' => $questionValue === 0 ? trim($validated['custom_security_question']) : null,
            'security_setup_completed' => true, // Admin/staff creates user with completed security
        ];

        try {
            $user = User::create($userData);

            ActivityLog::log('created', "Membuat pengguna: {$user->name}", $user);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pengguna berhasil ditambahkan. Kode referral: ' . $user->referral_code
                ]);
            }

            // ROLE-BASED REDIRECT - Fix navigation issue
            if (auth()->user()->role === 'staff') {
                // Staff should go to referral codes, not users
                return redirect()->route('referral-codes.index')
                    ->with('success', 'User berhasil ditambahkan. Kode referral: ' . $user->referral_code);
            }
            
            // Admin goes to users management
            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil ditambahkan. Kode referral: ' . $user->referral_code);
        } catch (\Exception $e) {
            Log::error('User creation failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_data' => array_merge($userData, ['password' => '[HIDDEN]'])
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat pengguna.'], 500);
            }

            return back()->with('error', 'Gagal membuat pengguna.')->withInput();
        }
    }

    /**
     * Tampilkan detail pengguna.
     */
    public function show(User $user): View
    {
        $user->load(['referrer', 'referrals']);
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
        $roles = [
            'admin' => 'Administrator',
            'staff' => 'Staff',
            'user' => 'User'
        ];
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
            'phone' => ['nullable', 'string', 'regex:/^(\+62|0)[0-9]{9,12}$/', 'max:20'],
            'role' => ['required', 'in:admin,staff,user'],
            'is_active' => ['boolean'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,gif,webp', 'max:2048'],
        ]);

        // Cek limit admin jika upgrade ke admin
        $currentRole = $user->role;
        $newRole = $validated['role'];
        $isUpgradeToAdmin = $newRole === 'admin' && $currentRole !== 'admin';
        
        if ($isUpgradeToAdmin) {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount >= 3) {
                $errorMsg = 'Jumlah Admin sudah mencapai batas maksimal (3 orang).';
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $errorMsg], 422);
                }
                return back()->withErrors(['role' => $errorMsg])->withInput();
            }
        }

        $userData = [
            'name' => trim($validated['name']),
            'email' => strtolower(trim($validated['email'])),
            'phone' => $validated['phone'] ? trim($validated['phone']) : null,
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active', true),
        ];

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        try {
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

            ActivityLog::log('updated', "Mengubah pengguna: {$user->name}", $user, $oldValues, $user->fresh()->toArray());

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Pengguna berhasil diperbarui.']);
            }

            return redirect()->route('users.index')
                ->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('User update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal memperbarui pengguna.'], 500);
            }

            return back()->with('error', 'Gagal memperbarui pengguna.')->withInput();
        }
    }

    /**
     * Soft delete pengguna (dapat dikembalikan).
     */
    public function destroy(User $user): RedirectResponse
    {
        // Tidak bisa hapus diri sendiri
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        // Tidak bisa hapus admin terakhir
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir.');
        }

        $userName = $user->name;

        // Soft delete - avatar tetap tersimpan untuk kemungkinan restore
        $user->delete();

        ActivityLog::log('deleted', "Menghapus pengguna: {$userName} (soft delete)");

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus dan dapat dikembalikan jika diperlukan.');
    }
}
