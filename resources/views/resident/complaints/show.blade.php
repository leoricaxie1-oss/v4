<x-layouts.app>
<div class="mx-auto max-w-4xl space-y-6">
    <a href="{{ route('resident.complaints.index') }}" class="text-sm text-brand-600 hover:underline">← Back</a>

    <div class="card"><div class="card-body">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-slate-500">Reference</p>
                <p class="font-mono text-lg">{{ $complaint->reference_no }}</p>
                <h1 class="mt-1 text-xl font-bold">{{ $complaint->title }}</h1>
                <p class="text-sm text-slate-500">Filed {{ $complaint->created_at->format('M d, Y') }}</p>
            </div>
            <x-status-badge :status="$complaint->status"/>
        </div>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Category</dt><dd>{{ $complaint->category }} @if($complaint->custom_category) — {{ $complaint->custom_category }} @endif</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Respondent</dt><dd>{{ $complaint->respondent_name }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Incident Date</dt><dd>{{ $complaint->incident_date->format('M d, Y') }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Location</dt><dd>{{ $complaint->incident_location }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase text-slate-500">Description</dt><dd class="whitespace-pre-line">{{ $complaint->description }}</dd></div>
            @if ($complaint->officer)
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Officer</dt><dd>{{ $complaint->officer->full_name }}</dd></div>
            @endif
        </dl>
    </div></div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="card"><div class="card-body">
            <h2 class="font-semibold">Mediation Schedule</h2>
            @forelse ($complaint->mediations as $m)
                <div class="mt-3 rounded border p-3 text-sm">
                    <p class="font-medium">{{ $m->scheduled_at->format('M d, Y h:i A') }}</p>
                    <p class="text-xs text-slate-500">Venue: {{ $m->venue }} · Officer: {{ $m->officer?->full_name ?? '—' }}</p>
                    <x-status-badge :status="$m->status"/>
                </div>
            @empty
                <p class="mt-3 text-sm text-slate-500">No mediation scheduled.</p>
            @endforelse
        </div></div>

        <div class="card"><div class="card-body">
            <h2 class="font-semibold">Hearing Schedule</h2>
            @forelse ($complaint->hearings as $h)
                <div class="mt-3 rounded border p-3 text-sm">
                    <p class="font-medium">{{ $h->scheduled_at->format('M d, Y h:i A') }}</p>
                    <p class="text-xs text-slate-500">Venue: {{ $h->venue }} · Officer: {{ $h->officer?->full_name ?? '—' }}</p>
                    <x-status-badge :status="$h->status"/>
                </div>
            @empty
                <p class="mt-3 text-sm text-slate-500">No hearing scheduled.</p>
            @endforelse
        </div></div>
    </div>

    @if ($complaint->evidence->count())
        <div class="card"><div class="card-body">
            <h2 class="font-semibold">Evidence</h2>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach ($complaint->evidence as $e)
                    <li class="flex items-center justify-between rounded border p-2">
                        <span>{{ $e->original_name }} ({{ round($e->size_bytes/1024) }} KB)</span>
                        <span class="text-xs text-slate-500">{{ $e->mime_type }}</span>
                    </li>
                @endforeach
            </ul>
        </div></div>
    @endif
</div>
</x-layouts.app>
