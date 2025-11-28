<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ReferralCode extends Model
{
    protected $fillable = [
        'code',
        'description',
        'created_by',
        'max_uses',
        'used_count',
        'is_active',
        'expires_at',
        'role',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
        'max_uses' => 'integer',
        'used_count' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($referralCode) {
            if (empty($referralCode->code)) {
                $referralCode->code = self::generateUniqueCode();
            }
            
            // Auto-set creator to current user for security
            if (auth()->check() && empty($referralCode->created_by)) {
                $referralCode->created_by = auth()->id();
            }
        });

        // Global scope for ownership security
        static::addGlobalScope('owner', function ($builder) {
            if (auth()->check() && !Gate::allows('referral-codes.manage')) {
                $builder->where('created_by', auth()->id());
            }
        });
    }

    /**
     * Generate unique referral code.
     */
    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Users who used this referral code.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'referral_code_usage')
            ->withPivot('used_at');
    }

    /**
     * Check if code is valid for use.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->max_uses !== null && $this->max_uses > 0 && $this->used_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Get status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) {
            return 'Nonaktif';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return 'Kadaluarsa';
        }

        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) {
            return 'Limit Tercapai';
        }

        return 'Aktif';
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status_label) {
            'Aktif' => 'badge-success',
            'Nonaktif' => 'badge-gray',
            'Kadaluarsa' => 'badge-warning',
            'Limit Tercapai' => 'badge-info',
            default => 'badge-gray',
        };
    }

    /**
     * Relationship: Creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Check if user can manage this specific referral code.
     */
    public function canBeManagedBy($user): bool
    {
        // Admin can manage all codes
        if ($user->role === 'admin') {
            return true;
        }
        
        // Users can only manage their own codes
        return $this->created_by === $user->id;
    }

    /**
     * Scope to get codes without global scope (for admin operations).
     */
    public function scopeWithoutOwnerScope($query)
    {
        return $query->withoutGlobalScope('owner');
    }
}
