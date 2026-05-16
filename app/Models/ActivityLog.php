<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'module', 'description',
        'ip_address', 'user_agent', 'metadata',
    ];

    protected $casts = ['metadata' => 'array'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?string $module = null, ?string $description = null, array $meta = []): void
    {
        self::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'module'     => $module,
            'description'=> $description,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'metadata'   => $meta,
        ]);
    }
}
