<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCode extends Model
{
    protected $fillable = [
        'type',
        'name',
        'data',
        'colors',
        'customization',
        'qr_image_path',
        'user_id',
        'scan_count',
        'is_dynamic',
        'redirect_slug',
    ];

    protected $casts = [
        'data' => 'array',
        'colors' => 'array',
        'customization' => 'array',
        'is_dynamic' => 'boolean',
    ];

    /**
     * Get the user who owns this QR code.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files associated with this QR code.
     */
    public function files()
    {
        return $this->hasMany(QrCodeFile::class);
    }

    /**
     * Check if QR code is owned by a guest (no user_id).
     */
    public function isGuest(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Check if QR code is owned by a free user.
     */
    public function isFreeUser(): bool
    {
        return $this->user && $this->user->isFree();
    }

    /**
     * Check if QR code is owned by a premium user.
     */
    public function isPremiumUser(): bool
    {
        return $this->user && $this->user->isPremium();
    }

    /**
     * Check if scan limit reached (10 for guest/free).
     */
    public function scanLimitReached(): bool
    {
        // Premium = unlimited
        if ($this->isPremiumUser()) {
            return false;
        }
        
        // Guest or free = 10 scans
        return $this->scan_count >= 10;
    }

    /**
     * Increment scan count.
     */
    public function incrementScanCount(): void
    {
        $this->increment('scan_count');
    }

    /**
     * Get the QR code type in a human-readable format.
     */
    public function getTypeNameAttribute()
    {
        return match($this->type) {
            'url' => 'Website URL',
            'email' => 'Email',
            'text' => 'Text',
            'pdf' => 'PDF',
            'menu' => 'Menu',
            'coupon' => 'Coupon',
            'event' => 'Event',
            'app' => 'App',
            'location' => 'Location',
            'wifi' => 'WiFi',
            'phone' => 'Phone',
            'mp3' => 'MP3',
            'business_card' => 'Business Card',
            'personal_vcard' => 'Personal vCard',
            default => ucfirst($this->type),
        };
    }
}
