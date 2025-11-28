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
        
        if (strlen($code) < 5) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referral minimal 5 karakter'
            ]);
        }

        $referralCode = ReferralCode::where('code', $code)->first();

        if (!$referralCode) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referral tidak ditemukan'
            ]);
        }

        if (!$referralCode->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Kode referral sudah tidak aktif atau kedaluwarsa'
            ]);
        }

        $referrer = User::find($referralCode->created_by);
        if (!$referrer || !$referrer->is_active) {
            return response()->json([
                'valid' => false,
                'message' => 'Akun pemilik kode referral tidak aktif'
            ]);
        }

        return response()->json([
            'valid' => true,
            'referrer_name' => $referrer->name,
            'role' => $referralCode->role
        ]);
    }

    /**
     * Proses registrasi (tanpa security questions).
     */
    public function store(Request $request): RedirectResponse
    {
        // In debug mode, let validation errors show naturally
        if (app()->environment('local', 'testing')) {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'phone' => ['nullable', 'string', 'max:20'], // Optional phone
                'referral_code' => ['nullable', 'string'],
            ], [
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal 8 karakter.',
                'email.unique' => 'Email sudah terdaftar.',
                'email.email' => 'Format email tidak valid.',
                'name.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]);
        } else {
            // Production mode - with custom error messages
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'phone' => ['nullable', 'string', 'max:20'], // Optional phone
                'referral_code' => ['nullable', 'string'],
            ], [
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'password.min' => 'Password minimal 8 karakter.',
                'email.unique' => 'Email sudah terdaftar.',
                'email.email' => 'Format email tidak valid.',
                'name.required' => 'Nama lengkap wajib diisi.',
                'email.required' => 'Email wajib diisi.',
                'password.required' => 'Password wajib diisi.',
            ]);
        }

        // Validate referral code if provided
        $referralCode = null;
        $assignedRole = 'user'; // default role
        
        if (!empty($validated['referral_code'])) {
            $referralCode = ReferralCode::where('code', strtoupper($validated['referral_code']))->first();
            
            if (!$referralCode) {
                return back()
                    ->withInput()
                    ->withErrors(['referral_code' => 'Kode referral tidak ditemukan.']);
            }
            
            if (!$referralCode->isValid()) {
                return back()
                    ->withInput()
                    ->withErrors(['referral_code' => 'Kode referral tidak valid atau sudah tidak aktif.']);
            }
            
            // Get role from referral code
            $assignedRole = $referralCode->role ?? 'user';
        }

        try {
            // Create user with assigned role
            $user = User::create([
                'name' => trim($validated['name']),
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'] ?? null, // Fix: Handle optional phone
                'role' => $assignedRole,
                'is_active' => true,
                'security_setup_completed' => false,
            ]);

            // Track referral code usage if used
            if ($referralCode) {
                $referralCode->increment('used_count');
                $referralCode->users()->attach($user->id, ['used_at' => now()]);
            }

            // Auto login
            Auth::login($user);
            ActivityLog::log('register', 'Registrasi akun baru' . ($referralCode ? ' dengan kode referral: ' . $referralCode->code : ' tanpa kode referral'));

            // Redirect ke setup security (WAJIB)
            return redirect()->route('security.setup');

        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage(), [
                'input' => $request->except(['password', 'password_confirmation'])
            ]);
            
            // In debug mode, show the actual error
            if (app()->environment('local', 'testing')) {
                return back()
                    ->withInput()
                    ->with('error', 'Registrasi gagal: ' . $e->getMessage());
            }
            
            // Production mode - generic error
            return back()
                ->withInput()
                ->with('error', 'Registrasi gagal. Silakan periksa kembali data Anda dan coba lagi.');
        }
    }

    /**
     * Tampilkan halaman setup security questions.
     */
    public function showSetupSecurity(): View|RedirectResponse
    {
        $user = Auth::user();

        // Jika sudah setup security question, redirect ke dashboard
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
        try {
            \Log::info('Security setup started', ['user_id' => Auth::id(), 'data' => $request->all()]);
            
            $securityQuestions = config('security_questions.questions');
            $validKeys = array_merge([0], array_keys($securityQuestions)); // 0 = custom
            
            \Log::info('Valid keys', ['keys' => $validKeys]);

            $validated = $request->validate([
                'birth_date' => ['required', 'date', 'before:today'],
                'security_question_1' => ['required', 'in:' . implode(',', $validKeys)],
                'custom_security_question' => ['required_if:security_question_1,0', 'nullable', 'string', 'max:255'],
                'security_answer_1' => ['required', 'string', 'min:3', 'max:255'],
            ], [
                'birth_date.required' => 'Tanggal lahir wajib diisi.',
                'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
                'security_question_1.required' => 'Pilih pertanyaan keamanan.',
                'custom_security_question.required_if' => 'Tulis pertanyaan custom Anda.',
                'security_answer_1.required' => 'Jawaban keamanan wajib diisi.',
                'security_answer_1.min' => 'Jawaban minimal 3 karakter.',
            ]);

            $user = Auth::user();
            $questionValue1 = (int) $validated['security_question_1'];

            // Determine the question to save (E-Surat-Perkim style)
            $questionToSave1 = $questionValue1;
            if ($questionValue1 === 0) {
                $questionToSave1 = 'custom:' . trim($validated['custom_security_question']);
            }

            $user->update([
                'birth_date' => $validated['birth_date'],
                'security_question_1' => $questionValue1, // Save as integer (0 for custom)
                'security_answer_1' => Hash::make(strtolower(trim($validated['security_answer_1']))),
                'custom_security_question' => $questionValue1 === 0 ? trim($validated['custom_security_question']) : null, // Save custom question separately
                'security_setup_completed' => true,
            ]);

            ActivityLog::log('security_setup', 'Setup keamanan akun selesai');

            return redirect()->route('dashboard')
                ->with('success', 'Setup keamanan berhasil. Selamat datang!');
                
        } catch (\Exception $e) {
            \Log::error('Security setup failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'input' => $request->except(['security_answer_1'])
            ]);
            
            // In debug mode, show actual error
            if (app()->environment('local', 'testing')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Setup keamanan gagal: ' . $e->getMessage());
            }
            
            // Production mode - user-friendly error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan setup keamanan. Silakan periksa data Anda dan coba lagi.');
        }
    }
}
