<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class VolunteerPointTransaction extends Model
{
    protected $fillable = ['volunteer_id', 'registration_id', 'points', 'type', 'description', 'balance_after'];
    public function registration(): BelongsTo
    {
        return $this->belongsTo(CleanupDriveRegistration::class);
    }
}
