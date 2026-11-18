<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WasteReport extends Model
{
    protected $fillable = [
        'user_id',
        'barangay_id',
        'zone',
        'title',
        'category',
        'description',
        'photo_path',
        'latitude',
        'longitude',
        'status',
    ];

    protected $casts = [
        'latitude'  => 'float',
        'longitude' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    /** "BB-0007" style code, built from the id so it needs no extra column. */
    public function getReferenceAttribute(): string
    {
        return 'BB-' . str_pad((string) $this->id, 4, '0', STR_PAD_LEFT);
    }

    /** Public URL of the photo. asset() uses whatever host the browser is on. */
    public function getPhotoUrlAttribute(): string
    {
        return asset('storage/' . $this->photo_path);
    }
}
