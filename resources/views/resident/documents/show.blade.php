<x-layouts.app>
<div class="mx-auto max-w-3xl">
    <a href="{{ route('resident.documents.index') }}" class="text-sm text-brand-600 hover:underline">← Back to my requests</a>

    <div class="card mt-3"><div class="card-body">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-slate-500">Reference</p>
                <p class="font-mono text-lg">{{ $document->reference_no }}</p>
            </div>
            <x-status-badge :status="$document->status"/>
        </div>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Document</dt><dd>{{ $document->document_label }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Fee</dt><dd>₱{{ number_format($document->fee, 2) }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Purpose</dt><dd>{{ $document->purpose }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Filed</dt><dd>{{ $document->created_at->format('M d, Y h:i A') }}</dd></div>
            @if ($document->pickup_date)
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Pickup date</dt><dd>{{ $document->pickup_date->format('M d, Y') }}</dd></div>
                <div><dt class="text-xs font-semibold uppercase text-slate-500">Pickup schedule</dt><dd>{{ $document->pickup_schedule }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase text-slate-500">Claim requirements</dt><dd class="whitespace-pre-line">{{ $document->claim_requirements }}</dd></div>
            @endif
            @if ($document->rejection_reason)
                <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase text-rose-500">Rejection reason</dt><dd>{{ $document->rejection_reason }}</dd></div>
            @endif
        </dl>

        @if ($document->status === 'pending_review')
            <form method="POST" action="{{ route('resident.documents.destroy', $document) }}" class="mt-6">
                @csrf @method('DELETE')
                <button class="btn-danger">Cancel request</button>
            </form>
        @endif
    </div></div>
</div>
</x-layouts.app>
