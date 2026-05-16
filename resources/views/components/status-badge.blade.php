@props(['status'])
@php
    $map = [
        'pending'                 => 'badge-warn',
        'pending_review'          => 'badge-warn',
        'approved'                => 'badge-success',
        'released'                => 'badge-success',
        'ready_for_pickup'        => 'badge-info',
        'rejected'                => 'badge-danger',
        'dismissed'               => 'badge-gray',
        'resolved'                => 'badge-success',
        'under_investigation'     => 'badge-warn',
        'scheduled_for_mediation' => 'badge-info',
        'scheduled_for_hearing'   => 'badge-info',
        'escalated'               => 'badge-danger',
        'open'                    => 'badge-warn',
        'closed'                  => 'badge-gray',
        'cancelled'               => 'badge-gray',
        'suspended'               => 'badge-danger',
    ];
    $cls = $map[$status] ?? 'badge-gray';
@endphp
<span class="{{ $cls }}">{{ \Illuminate\Support\Str::headline($status) }}</span>
