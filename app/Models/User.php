<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'suffix',
        'email', 'phone', 'password', 'avatar_path',
        'account_status',
        'approved_by_secretary', 'approved_by_secretary_id', 'approved_by_secretary_at',
        'approved_by_kagawad',   'approved_by_kagawad_id',   'approved_by_kagawad_at',
        'approved_by_captain',   'approved_by_captain_id',   'approved_by_captain_at',
        'rejection_reason',
        'failed_login_attempts', 'locked_until',
        'last_login_at', 'last_login_ip',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'        => 'datetime',
            'password'                 => 'hashed',
            'approved_by_secretary'    => 'boolean',
            'approved_by_kagawad'      => 'boolean',
            'approved_by_captain'      => 'boolean',
            'approved_by_secretary_at' => 'datetime',
            'approved_by_kagawad_at'   => 'datetime',
            'approved_by_captain_at'   => 'datetime',
            'locked_until'             => 'datetime',
            'last_login_at'            => 'datetime',
        ];
    }

    // ---------------------------------------------------------------
    // Computed
    // ---------------------------------------------------------------

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix,
        ])));
    }

    public function getInitialsAttribute(): string
    {
        return Str::upper(Str::substr($this->first_name, 0, 1).Str::substr($this->last_name, 0, 1));
    }

    public function isFullyApproved(): bool
    {
        return $this->account_status === 'approved'
            && $this->approved_by_secretary
            && $this->approved_by_kagawad
            && $this->approved_by_captain;
    }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    public function resident(): HasOne
    {
        return $this->hasOne(Resident::class);
    }

    public function skillServices(): HasMany
    {
        return $this->hasMany(SkillService::class);
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class, 'owner_user_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function complaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'complainant_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class)->withPivot('last_read_at')->withTimestamps();
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}
