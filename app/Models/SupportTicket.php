<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id',
        'waste_report_id',
        'volunteer_violation_id',
        'category',
        'title',
        'description',
        'attachment_path',
        'attachment_original_name',
        'attachment_mime_type',
        'status',
        'assigned_to',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wasteReport(): BelongsTo
    {
        return $this->belongsTo(WasteReport::class);
    }

    public function volunteerViolation(): BelongsTo
    {
        return $this->belongsTo(VolunteerViolation::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id')->orderBy('created_at');
    }

    public function isActive(): bool
    {
        return in_array($this->status, config('ticketing.active_statuses', []), true);
    }

    public function getReferenceAttribute(): string
    {
        return 'BB-TKT-' . str_pad((string) $this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getCategoryLabelAttribute(): string
    {
        return config('ticketing.categories.' . $this->category, 'Other Concern');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'In Progress',
            'waiting_for_user' => 'Waiting for You',
            'resolved' => 'Resolved',
            'closed' => 'Closed',
            default => 'Open',
        };
    }
}
