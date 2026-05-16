<?php

namespace App\Models;

use App\Models\Concerns\HasApprovalChain;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SkillService extends Model
{
    use SoftDeletes, HasApprovalChain;

    protected $fillable = [
        'user_id', 'category', 'custom_category', 'title', 'description',
        'rate', 'rate_unit', 'contact_email', 'contact_phone', 'photo_path', 'tags',
        'status', 'is_active',
        'approved_by_secretary', 'approved_by_secretary_id', 'approved_by_secretary_at',
        'approved_by_kagawad',   'approved_by_kagawad_id',   'approved_by_kagawad_at',
        'approved_by_captain',   'approved_by_captain_id',   'approved_by_captain_at',
        'rejection_reason',
    ];

    protected $casts = [
        'tags'                    => 'array',
        'rate'                    => 'decimal:2',
        'rating_avg'              => 'decimal:2',
        'is_active'               => 'boolean',
        'approved_by_secretary'   => 'boolean',
        'approved_by_kagawad'     => 'boolean',
        'approved_by_captain'     => 'boolean',
        'approved_by_secretary_at'=> 'datetime',
        'approved_by_kagawad_at'  => 'datetime',
        'approved_by_captain_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function scopePublic($query)
    {
        return $query->where('status', 'approved')->where('is_active', true);
    }

    public function getDisplayCategoryAttribute(): string
    {
        return $this->category === 'Others' && $this->custom_category
            ? $this->custom_category
            : $this->category;
    }
}
