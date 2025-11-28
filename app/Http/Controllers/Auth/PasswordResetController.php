<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetController extends Controller
{
    /**
     * Tampilkan form input email.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request (Step 1: Email).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Double check user exists
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem.',
            ]);
        }

        // Cek apakah user punya security questions
        if (!$user->hasSecurityQuestions()) {
            return back()->withErrors([
                'email' => 'Akun ini belum mengatur pertanyaan keamanan. Hubungi administrator.',
            ]);
        }

        // Store email in session for next step (E-Surat-Perkim style)
        session(['reset_email' => $request->email]);

        return redirect()->route('password.security');
    }

    /**
     * Tampilkan form pertanyaan keamanan (Step 2: Security Questions).
     */
    public function showSecurityQuestions(): View|RedirectResponse
    {
        $email = session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi proses reset password.']);
        }

        $user = User::where('email', $email)->first();
        $securityQuestions = config('security_questions.questions', []);

        // Handle custom questions - FIXED LOGIC
        $questionText = 'Pertanyaan Keamanan';
        
        if ($user->security_question_1 === 0 && $user->custom_security_question) {
            // Custom question
            $questionText = $user->custom_security_question;
        } else {
            // Standard question
            $questionText = $securityQuestions[$user->security_question_1] ?? 'Pertanyaan Keamanan';
        }

        return view('auth.security-questions', [
            'email' => $email,
            'question1' => $questionText,
        ]);
    }

    /**
     * Verifikasi jawaban pertanyaan keamanan (Step 3: Verify Security).
     */
    public function verifySecurityQuestions(Request $request): RedirectResponse
    {
        $email = session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi tidak valid. Silakan ulangi proses reset password.']);
        }

        $user = User::where('email', $email)->first();

        // Validate birth date + security answer (E-Surat-Perkim style)
        $request->validate([
            'birth_date' => ['required', 'date'],
            'answer1' => ['required', 'string'],
        ], [
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'answer1.required' => 'Jawaban pertanyaan keamanan wajib diisi.',
        ]);

        // Verify birth date
        $userBirthDate = $user->birth_date ? $user->birth_date->format('Y-m-d') : null;
        $providedBirthDate = $request->birth_date;
        
        if ($userBirthDate !== $providedBirthDate) {
            return back()->withErrors([
                'birth_date' => 'Tanggal lahir tidak sesuai.',
            ]);
        }

        // Verify security question 1 (user's set question)
        $answer1Valid = Hash::check(strtolower(trim($request->answer1)), $user->security_answer_1);
        
        if (!$answer1Valid) {
            return back()->withErrors([
                'answer1' => 'Jawaban pertanyaan keamanan tidak sesuai.',
            ]);
        }

        // Generate reset token dan simpan di session
        $token = Str::random(64);
        session(['password_reset' => [
            'token' => $token,
            'email' => $user->email,
            'verified' => true,
            'expires_at' => now()->addMinutes(15),
        ]]);

        return redirect()->route('password.reset', $token);
    }

    /**
     * Tampilkan form reset password.
     */
    public function showResetForm(string $token): View|RedirectResponse
    {
        $reset = session('password_reset');

        if (!$reset || $reset['token'] !== $token || !($reset['verified'] ?? false) || now()->isAfter($reset['expires_at'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password.
     */
    public function reset(Request $request): RedirectResponse
    {
        $reset = session('password_reset');

        if (!$reset || !($reset['verified'] ?? false) || now()->isAfter($reset['expires_at'])) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Sesi reset password tidak valid. Silakan ulangi.']);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', $reset['email'])->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus session reset
        session()->forget('password_reset');

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }
}
