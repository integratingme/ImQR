<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrameDesign extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'design_json',
        'svg_content',
        'thumbnail_url',
        'is_template',
    ];

    protected $casts = [
        'design_json' => 'array',
        'is_template' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOwnedByOrTemplate(User $user): bool
    {
        return $this->is_template || $this->user_id === $user->id;
    }
}
