<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class VolunteerViolation extends Model
{
    protected $fillable = ['volunteer_id', 'registration_id', 'type', 'description', 'recorded_at', 'points_deducted', 'status'];
    protected $casts = ['recorded_at' => 'datetime'];
    public function registration(): BelongsTo
    {
        return $this->belongsTo(CleanupDriveRegistration::class);
    }
}
