<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class VolunteerApplication extends Model
{
    protected $fillable = ['user_id', 'status', 'id_type', 'id_number', 'id_front_path', 'id_back_path', 'guardian_name', 'guardian_phone', 'organization_name', 'organization_type', 'organization_role', 'skills', 'availability', 'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship', 'reviewed_by', 'reviewed_at', 'review_notes'];
    protected $casts = ['skills' => 'array', 'availability' => 'array', 'reviewed_at' => 'datetime'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
