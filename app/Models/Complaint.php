<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Complaint extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_no', 'complainant_id', 'title', 'category', 'custom_category',
        'respondent_name', 'incident_date', 'incident_location', 'description',
        'witness_name', 'witness_contact',
        'status', 'assigned_officer_id', 'resolution_notes', 'resolved_at',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'resolved_at'   => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $c) {
            if (! $c->reference_no) {
                $c->reference_no = 'CMP-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
            }
        });
    }

    public function complainant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'complainant_id');
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ComplaintEvidence::class);
    }

    public function mediations(): HasMany
    {
        return $this->hasMany(MediationSchedule::class);
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(HearingSchedule::class);
    }

    public function blotter(): HasMany
    {
        return $this->hasMany(BlotterRecord::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_review'         => 'Pending Review',
            'under_investigation'    => 'Under Investigation',
            'scheduled_for_mediation'=> 'Scheduled for Mediation',
            'scheduled_for_hearing'  => 'Scheduled for Hearing',
            'resolved'               => 'Resolved',
            'dismissed'              => 'Dismissed',
            'escalated'              => 'Escalated',
            default                  => Str::headline($this->status),
        };
    }
}
