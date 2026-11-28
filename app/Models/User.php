<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'first_name', 'middle_name', 'last_name', 'birthdate', 'sex', 'mobile', 'barangay_id', 'zone', 'address', 'role', 'email', 'password', 'volunteer_points', 'profile_photo_path'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'birthdate' => 'date',
        'email_verified_at' => 'datetime',
    ];

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function wasteReports(): HasMany
    {
        return $this->hasMany(WasteReport::class);
    }

    public function volunteerApplications(): HasMany
    {
        return $this->hasMany(VolunteerApplication::class);
    }
    public function cleanupRegistrations(): HasMany
    {
        return $this->hasMany(CleanupDriveRegistration::class, 'volunteer_id');
    }
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(VolunteerPointTransaction::class, 'volunteer_id');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function supportTicketMessages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class, 'sender_id');
    }


    public function getInitialsAttribute(): string
    {
        $parts = preg_split('/\s+/', trim((string) $this->name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= function_exists('mb_substr') ? mb_substr($part, 0, 1) : substr($part, 0, 1);
        }
        return strtoupper($initials ?: 'U');
    }

    public function isResident(): bool
    {
        return $this->role === 'resident';
    }
    public function isVolunteer(): bool
    {
        return $this->role === 'volunteer';
    }
}
