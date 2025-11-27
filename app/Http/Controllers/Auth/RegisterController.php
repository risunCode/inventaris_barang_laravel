<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Validate referral code (API).
     */
    public function validateReferral(Request $request): JsonResponse
    {
        $code = strtoupper(trim($request->query('code', '')));
        
        if (strlen($code) < 8) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referral minimal 8 karakter'
            ]);
        }

        $referrer = User::where('referral_code', $code)->first();

        if (!$referrer) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referral tidak ditemukan'
            ]);
        }

        if (!$referrer->is_active) {
            return response()->json([
                'valid' => false,
                'message' => 'Akun pemilik kode referral tidak aktif'
            ]);
        }

        return response()->json([
            'valid' => true,
            'referrer_name' => $referrer->name
        ]);
    }

    /**
     * Proses registrasi (tanpa security questions).
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi referral code
        $referrer = User::where('referral_code', $request->referral_code)->first();

        if (!$referrer || !$referrer->is_active) {
            return back()->withErrors(['referral_code' => 'Kode referral tidak valid.']);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'referral_code' => ['required', 'exists:users,referral_code'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'referred_by' => $referrer->id,
            'is_active' => true,
            'security_setup_completed' => false,
        ]);

        // Assign role staff (default untuk user baru)
        $user->assignRole('staff');

        // Auto login
        Auth::login($user);
        ActivityLog::log('register', 'Registrasi akun baru');

        // Redirect ke setup security
        return redirect()->route('auth.setup-security');
    }

    /**
     * Tampilkan halaman setup security questions.
     */
    public function showSetupSecurity(): View|RedirectResponse
    {
        $user = Auth::user();

        // Jika sudah setup, redirect ke dashboard
        if ($user->security_setup_completed) {
            return redirect()->route('dashboard');
        }

        $securityQuestions = config('security_questions.questions');

        return view('auth.setup-security', compact('securityQuestions'));
    }

    /**
     * Simpan security question.
     */
    public function storeSetupSecurity(Request $request): RedirectResponse
    {
        $securityQuestions = config('security_questions.questions');
        $validKeys = array_merge([0], array_keys($securityQuestions)); // 0 = custom

        $validated = $request->validate([
            'birth_date' => ['required', 'date', 'before:today'],
            'security_question_1' => ['required', 'in:' . implode(',', $validKeys)],
            'custom_security_question' => ['required_if:security_question_1,0', 'nullable', 'string', 'max:255'],
            'security_answer_1' => ['required', 'string', 'min:3', 'max:255'],
        ], [
            'custom_security_question.required_if' => 'Tulis pertanyaan custom Anda.',
            'security_answer_1.min' => 'Jawaban minimal 3 karakter.',
        ]);

        $user = Auth::user();
        $questionValue = (int) $validated['security_question_1'];

        $updateData = [
            'birth_date' => $validated['birth_date'],
            'security_question_1' => $questionValue,
            'security_answer_1' => Hash::make(strtolower(trim($validated['security_answer_1']))),
            'security_setup_completed' => true,
        ];

        // Handle custom question (0 = custom)
        if ($questionValue === 0) {
            $updateData['custom_security_question'] = trim($validated['custom_security_question']);
        } else {
            $updateData['custom_security_question'] = null;
        }

        $user->update($updateData);

        ActivityLog::log('security_setup', 'Setup keamanan akun selesai');

        return redirect()->route('dashboard')
            ->with('success', 'Setup keamanan berhasil. Selamat datang!');
    }
}
