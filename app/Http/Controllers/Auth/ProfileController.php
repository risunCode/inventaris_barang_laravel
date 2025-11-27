<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil.
     */
    public function edit(): View
    {
        return view('auth.profile', [
            'user' => Auth::user(),
            'securityQuestions' => config('security_questions.questions'),
        ]);
    }

    /**
     * Update profil user.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Check if this is avatar-only update (from crop modal)
        $isAvatarOnly = $request->hasFile('avatar') && !$request->has('name');

        if ($isAvatarOnly) {
            $request->validate([
                'avatar' => ['required', 'image', 'max:2048'],
            ]);

            // Hapus avatar lama
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->update([
                'avatar' => $request->file('avatar')->store('avatars', 'public')
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Foto profil berhasil diperbarui.']);
            }

            return back()->with('success', 'Foto profil berhasil diperbarui.');
        }

        // Normal profile update
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui.');
    }

    /**
     * Update pertanyaan keamanan.
     */
    public function updateSecurity(Request $request): RedirectResponse
    {
        $questions = config('security_questions.questions');
        $validKeys = array_merge(array_keys($questions), [0]); // 0 = custom

        $validated = $request->validate([
            'security_question_1' => ['required', 'in:' . implode(',', $validKeys)],
            'custom_security_question' => ['required_if:security_question_1,0', 'nullable', 'string', 'max:255'],
            'security_answer_1' => ['required', 'string', 'max:255'],
        ], [
            'security_question_1.required' => 'Pilih pertanyaan keamanan.',
            'custom_security_question.required_if' => 'Tulis pertanyaan custom Anda.',
            'security_answer_1.required' => 'Masukkan jawaban keamanan.',
        ]);

        $user = Auth::user();
        
        $questionValue = (int) $validated['security_question_1'];
        
        $updateData = [
            'security_question_1' => $questionValue,
            'security_answer_1' => Hash::make(strtolower(trim($validated['security_answer_1']))),
        ];

        // Handle custom question (0 = custom)
        if ($questionValue === 0) {
            $updateData['custom_security_question'] = $validated['custom_security_question'];
        } else {
            $updateData['custom_security_question'] = null;
        }

        $user->update($updateData);

        ActivityLog::log('security_update', 'Mengubah pertanyaan keamanan');

        return back()->with('success', 'Pertanyaan keamanan berhasil diperbarui.');
    }
}
