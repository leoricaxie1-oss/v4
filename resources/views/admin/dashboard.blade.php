<x-layouts.app>
<h1 class="text-2xl font-bold">Admin Dashboard</h1>
<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-stat-card label="Total Users"        :value="$users_count"       tone="brand"/>
    <x-stat-card label="Pending Residents"  :value="$pending_residents" tone="warn"/>
    <x-stat-card label="Activity Logs (10)" :value="$recent_activity->count()" tone="gray"/>
    <x-stat-card label="Recent Logins"      :value="$recent_logins->count()"   tone="success"/>
</div>

<div class="mt-6 grid gap-4 lg:grid-cols-2">
    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Recent activity</h2>
        <ul class="mt-3 space-y-1 text-sm">
            @foreach ($recent_activity as $a)
                <li>{{ $a->user?->email ?? 'system' }} — {{ $a->description }} <span class="text-xs text-slate-400">({{ $a->created_at->diffForHumans() }})</span></li>
            @endforeach
        </ul>
    </div></div>
    <div class="card"><div class="card-body">
        <h2 class="font-semibold">Recent logins</h2>
        <ul class="mt-3 space-y-1 text-sm">
            @foreach ($recent_logins as $l)
                <li>{{ $l->email ?? $l->user?->email }} — <x-status-badge :status="$l->result"/> <span class="text-xs text-slate-400">{{ $l->created_at->diffForHumans() }}</span></li>
            @endforeach
        </ul>
    </div></div>
</div>
</x-layouts.app>
