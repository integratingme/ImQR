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
    ];

    protected $casts = [
        'data' => 'array',
        'colors' => 'array',
        'customization' => 'array',
    ];

    /**
     * Get the files associated with this QR code.
     */
    public function files()
    {
        return $this->hasMany(QrCodeFile::class);
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
            default => ucfirst($this->type),
        };
    }
}
