<?php

namespace App\Models;

use App\Models\Concerns\HasApprovalChain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use SoftDeletes, HasApprovalChain;

    protected $fillable = [
        'owner_user_id', 'business_name', 'business_type', 'description', 'address',
        'purok_id', 'contact_email', 'contact_phone', 'logo_path',
        'permit_number', 'permit_issued_at', 'permit_expires_at',
        'last_inspection_at', 'next_inspection_at',
        'status',
        'approved_by_secretary', 'approved_by_secretary_id', 'approved_by_secretary_at',
        'approved_by_kagawad',   'approved_by_kagawad_id',   'approved_by_kagawad_at',
        'approved_by_captain',   'approved_by_captain_id',   'approved_by_captain_at',
        'rejection_reason',
    ];

    protected $casts = [
        'permit_issued_at'   => 'date',
        'permit_expires_at'  => 'date',
        'last_inspection_at' => 'date',
        'next_inspection_at' => 'date',
        'rating_avg'         => 'decimal:2',
        'approved_by_secretary' => 'boolean',
        'approved_by_kagawad'   => 'boolean',
        'approved_by_captain'   => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function purok(): BelongsTo
    {
        return $this->belongsTo(Purok::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function scopePublic($query)
    {
        return $query->where('status', 'approved');
    }
}
