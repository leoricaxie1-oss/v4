<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediationSchedule extends Model
{
    use SoftDeletes;

    protected $table = 'mediation_schedule';

    protected $fillable = [
        'complaint_id', 'scheduled_at', 'venue',
        'assigned_officer_id', 'status', 'outcome',
    ];

    protected $casts = ['scheduled_at' => 'datetime'];

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function officer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }
}
