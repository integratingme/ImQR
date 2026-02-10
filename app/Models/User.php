<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan',
        'plan_expires_at',
        'custom_logo_count',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'plan_expires_at' => 'datetime',
        ];
    }

    /**
     * Check if user is on free plan.
     */
    public function isFree(): bool
    {
        return $this->plan === 'free';
    }

    /**
     * Check if user is on premium plan (and not expired).
     */
    public function isPremium(): bool
    {
        if ($this->plan !== 'premium') {
            return false;
        }
        
        // If no expiry set, premium is valid indefinitely
        if (!$this->plan_expires_at) {
            return true;
        }
        
        // Check if not expired
        return $this->plan_expires_at->isFuture();
    }

    /**
     * Check if free user can add custom logo (max 1).
     */
    public function canAddCustomLogo(): bool
    {
        if ($this->isPremium()) {
            return true;
        }
        
        return $this->custom_logo_count < 1;
    }

    /**
     * Get QR codes owned by this user.
     */
    public function qrCodes()
    {
        return $this->hasMany(\App\Models\QrCode::class);
    }
}
