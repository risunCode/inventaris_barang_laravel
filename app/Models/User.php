<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'birth_date',
        'is_active',
        'referral_code',
        'referred_by',
        'security_question_1',
        'security_answer_1',
        'custom_security_question',
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
        return !empty($this->security_question_1) && !empty($this->security_answer_1);
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
     * Cek apakah bisa menambah superadmin/admin.
     */
    public static function canAddAdmin(): bool
    {
        $adminCount = self::role(['super-admin', 'admin'])->count();
        return $adminCount < 3;
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
