<x-layouts.app>
<h1 class="text-2xl font-bold">Kagawad Dashboard</h1>
<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-stat-card label="Residents Pending" :value="$pending_residents" tone="warn"/>
    <x-stat-card label="Skills Pending"    :value="$pending_skills"    tone="success"/>
    <x-stat-card label="Businesses Pending" :value="$pending_business" tone="success"/>
    <x-stat-card label="Recent Complaints" :value="$recent_complaints->count()" tone="brand"/>
</div>
<div class="card mt-6"><div class="card-body">
    <h2 class="font-semibold">Recent complaints</h2>
    <ul class="mt-3 space-y-2 text-sm">
        @foreach ($recent_complaints as $c)
            <li class="flex items-center justify-between border-b py-2 last:border-0">
                <span>{{ $c->title }}</span><x-status-badge :status="$c->status"/>
            </li>
        @endforeach
    </ul>
</div></div>
</x-layouts.app>
