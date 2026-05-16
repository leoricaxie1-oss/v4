<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'reference_no', 'user_id', 'document_type', 'purpose', 'details', 'fee',
        'status', 'pickup_date', 'pickup_schedule', 'claim_requirements',
        'processed_by_id', 'processed_at', 'released_at', 'rejection_reason',
    ];

    protected $casts = [
        'pickup_date'  => 'date',
        'processed_at' => 'datetime',
        'released_at'  => 'datetime',
        'fee'          => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $doc) {
            if (! $doc->reference_no) {
                $doc->reference_no = 'DOC-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by_id');
    }

    public function getDocumentLabelAttribute(): string
    {
        return config("panipone.documents.{$this->document_type}.label", $this->document_type);
    }
}
