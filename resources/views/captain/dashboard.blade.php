<x-layouts.app>
<h1 class="text-2xl font-bold">Captain — Executive Dashboard</h1>
<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
    <x-stat-card label="Total Residents"          :value="$total_residents"      tone="brand"   icon="👥"/>
    <x-stat-card label="Awaiting Final Approval"  :value="$pending_residents"    tone="warn"    icon="✓"/>
    <x-stat-card label="Unresolved Complaints"    :value="$unresolved_complaints" tone="danger" icon="!"/>
    <x-stat-card label="Monthly Document Requests":value="$monthly_documents"    tone="success" icon="📄"/>
</div>

<div class="card mt-6"><div class="card-body">
    <h2 class="font-semibold">Escalated complaints</h2>
    <ul class="mt-3 space-y-2 text-sm">
        @forelse ($escalated as $c)
            <li class="flex items-center justify-between border-b py-2 last:border-0">
                <span>{{ $c->title }} — {{ $c->complainant->full_name }}</span>
                <x-status-badge :status="$c->status"/>
            </li>
        @empty
            <li class="text-slate-500">No escalated complaints.</li>
        @endforelse
    </ul>
</div></div>
</x-layouts.app>
