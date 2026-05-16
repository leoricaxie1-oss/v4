<x-layouts.app>
<div class="mb-4 flex items-center justify-between">
    <h1 class="text-2xl font-bold">My Complaints</h1>
    <a href="{{ route('resident.complaints.create') }}" class="btn-primary">+ File Complaint</a>
</div>

<div class="card overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Reference</th><th class="p-3">Title</th><th class="p-3">Category</th><th class="p-3">Status</th><th class="p-3">Filed</th><th class="p-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($complaints as $c)
                <tr>
                    <td class="p-3 font-mono text-xs">{{ $c->reference_no }}</td>
                    <td class="p-3">{{ $c->title }}</td>
                    <td class="p-3">{{ $c->category }}</td>
                    <td class="p-3"><x-status-badge :status="$c->status"/></td>
                    <td class="p-3">{{ $c->created_at->format('M d, Y') }}</td>
                    <td class="p-3 text-right"><a href="{{ route('resident.complaints.show', $c) }}" class="text-brand-600 hover:underline">Track</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-500">No complaints filed.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $complaints->links() }}</div>
</x-layouts.app>
