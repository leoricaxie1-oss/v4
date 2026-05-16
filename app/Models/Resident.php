<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'household_id',
        'province_id', 'city_id', 'barangay_id', 'purok_id',
        'birthdate', 'sex', 'civil_status', 'occupation', 'citizenship', 'religion',
        'is_senior_citizen', 'is_pwd', 'is_solo_parent', 'is_voter', 'is_household_head',
        'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relation',
    ];

    protected $casts = [
        'birthdate'           => 'date',
        'is_senior_citizen'   => 'boolean',
        'is_pwd'              => 'boolean',
        'is_solo_parent'      => 'boolean',
        'is_voter'            => 'boolean',
        'is_household_head'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function household(): BelongsTo
    {
        return $this->belongsTo(Household::class);
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function barangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class);
    }

    public function purok(): BelongsTo
    {
        return $this->belongsTo(Purok::class);
    }

    public function getAgeAttribute(): ?int
    {
        return $this->birthdate?->age;
    }
}
