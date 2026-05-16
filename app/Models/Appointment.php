<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_no', 'user_id', 'purpose', 'scheduled_at',
        'queue_number', 'status', 'handled_by_id', 'notes',
    ];

    protected $casts = ['scheduled_at' => 'datetime'];

    protected static function booted(): void
    {
        static::creating(function (self $a) {
            if (! $a->reference_no) {
                $a->reference_no = 'APT-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by_id');
    }
}
