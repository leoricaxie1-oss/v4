<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Household extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'household_code', 'purok_id', 'street_address',
        'head_resident_id', 'members_count', 'utilities', 'notes',
    ];

    protected $casts = ['utilities' => 'array'];

    public function purok(): BelongsTo
    {
        return $this->belongsTo(Purok::class);
    }

    public function head(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'head_resident_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(Resident::class);
    }
}
