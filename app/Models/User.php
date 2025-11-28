<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'phone',
        'avatar',
        'birth_date',
        'father_name',
        'is_active',
        'role',
        'referral_code',
        'referred_by',
        'security_question', // E-Surat-Perkim style field
        'security_answer', // E-Surat-Perkim style field
        'security_question_1',
        'security_answer_1',
        'security_question_2',
        'security_answer_2',
        'custom_security_question',
        'custom_security_question_2',
        'custom_security_answer',
        'security_setup_completed',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'security_setup_completed' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate referral code
        static::creating(function ($model) {
            if (empty($model->referral_code)) {
                $model->referral_code = self::generateReferralCode();
            }
        });
    }

    /**
     * Generate unique referral code.
     * Format: INV-XXXXXX-XXXXXX (20 chars)
     */
    public static function generateReferralCode(): string
    {
        do {
            $code = 'INV-' . strtoupper(Str::random(6)) . '-' . strtoupper(Str::random(6));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_answer_1',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Cek apakah user sudah setup security question.
     */
    public function hasSecurityQuestion(): bool
    {
        // More robust checking - handle different data types
        $hasCompleted = (bool) $this->security_setup_completed;
        $hasQuestion = !is_null($this->security_question_1) && $this->security_question_1 !== '';
        $hasAnswer = !is_null($this->security_answer_1) && $this->security_answer_1 !== '';
        
        return $hasCompleted && $hasQuestion && $hasAnswer;
    }

    /**
     * Cek apakah user sudah setup security untuk forgot-password (E-Surat-Perkim style).
     * Required: birth date + security question 1 + answer 1.
     */
    public function hasSecurityQuestions(): bool
    {
        // Debug: Log nilai actual untuk troubleshooting
        $hasCompleted = (bool) $this->security_setup_completed;
        $hasBirthDate = !is_null($this->birth_date) && $this->birth_date !== '';
        $hasQuestion1 = !is_null($this->security_question_1) && $this->security_question_1 !== '';
        $hasAnswer1 = !is_null($this->security_answer_1) && $this->security_answer_1 !== '';
        
        \Log::info('Security Check Debug:', [
            'user_id' => $this->id,
            'security_setup_completed' => $this->security_setup_completed,
            'birth_date' => $this->birth_date,
            'security_question_1' => $this->security_question_1,
            'security_answer_1' => !is_null($this->security_answer_1) && $this->security_answer_1 !== '' ? 'SET' : 'EMPTY',
            'hasCompleted' => $hasCompleted,
            'hasBirthDate' => $hasBirthDate,
            'hasQuestion1' => $hasQuestion1,
            'hasAnswer1' => $hasAnswer1,
            'final_result' => $hasCompleted && $hasBirthDate && $hasQuestion1 && $hasAnswer1
        ]);
        
        // Forgot password butuh: birth date + security question 1 + answer 1 (E-Surat-Perkim style)
        return $hasCompleted && $hasBirthDate && $hasQuestion1 && $hasAnswer1;
    }

    /**
     * Verify security answer (case-insensitive).
     */
    public function verifySecurityAnswer(string $answer): bool
    {
        return \Illuminate\Support\Facades\Hash::check(
            strtolower(trim($answer)),
            $this->security_answer_1
        );
    }

    /**
     * Get pertanyaan keamanan text.
     */
    public function getSecurityQuestionTextAttribute(): ?string
    {
        if ($this->security_question_1 === 0) {
            return $this->custom_security_question;
        }
        $questions = config('security_questions.questions');
        return $questions[$this->security_question_1] ?? null;
    }

    /**
     * Commodities yang dibuat oleh user ini.
     */
    public function commodities(): HasMany
    {
        return $this->hasMany(Commodity::class, 'created_by');
    }

    /**
     * Transfers yang diajukan oleh user ini.
     */
    public function transfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'requested_by');
    }

    /**
     * Disposals yang diajukan oleh user ini.
     */
    public function disposals(): HasMany
    {
        return $this->hasMany(Disposal::class, 'requested_by');
    }

    /**
     * Activity logs oleh user ini.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * User yang mereferensikan.
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Users yang direferensikan oleh user ini.
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Scope untuk user aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if user can add admins.
     */
    public function canAddAdmin(): bool
    {
        // Only super admin (or first user) can add admins
        return $this->id === 1;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === \App\Enums\Role::ADMIN->value;
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->role === \App\Enums\Role::STAFF->value;
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return $this->role === \App\Enums\Role::USER->value;
    }

    /**
     * Get user's referral codes usage.
     */
    public function usedReferralCodes(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\ReferralCode::class, 'referral_code_usage')
            ->withPivot('used_at');
    }

    /**
     * Get avatar URL atau default.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=3b82f6&color=fff';
    }
}
