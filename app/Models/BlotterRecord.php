<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class BlotterRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_no', 'complaint_id', 'recorded_by_id',
        'incident_type', 'narrative', 'location', 'incident_at',
        'parties_involved', 'status', 'investigation_notes',
    ];

    protected $casts = [
        'incident_at'      => 'datetime',
        'parties_involved' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $b) {
            if (! $b->reference_no) {
                $b->reference_no = 'BLT-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
            }
        });
    }

    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_id');
    }
}
