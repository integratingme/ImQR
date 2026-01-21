<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrCodeFile extends Model
{
    protected $fillable = [
        'qr_code_id',
        'file_type',
        'file_path',
        'original_name',
        'file_size',
    ];

    /**
     * Get the QR code that owns this file.
     */
    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    /**
     * Get the full URL to the file.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
