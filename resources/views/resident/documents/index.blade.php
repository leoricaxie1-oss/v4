<x-layouts.app>
<div class="mb-4 flex items-center justify-between">
    <h1 class="text-2xl font-bold">My Document Requests</h1>
    <a href="{{ route('resident.documents.create') }}" class="btn-primary">+ New Request</a>
</div>

<div class="card overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th class="p-3">Reference</th><th class="p-3">Type</th>
                <th class="p-3">Status</th><th class="p-3">Pickup</th>
                <th class="p-3">Filed</th><th class="p-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($documents as $d)
                <tr>
                    <td class="p-3 font-mono text-xs">{{ $d->reference_no }}</td>
                    <td class="p-3">{{ $d->document_label }}</td>
                    <td class="p-3"><x-status-badge :status="$d->status"/></td>
                    <td class="p-3">{{ $d->pickup_date?->format('M d, Y') ?? '—' }}</td>
                    <td class="p-3">{{ $d->created_at->format('M d, Y') }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('resident.documents.show', $d) }}" class="text-brand-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-500">No document requests yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $documents->links() }}</div>
</x-layouts.app>
