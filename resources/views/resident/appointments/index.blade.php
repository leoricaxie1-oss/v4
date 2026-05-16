<x-layouts.app>
<div class="mb-4 flex items-center justify-between">
    <h1 class="text-2xl font-bold">My Appointments</h1>
    <a href="{{ route('resident.appointments.create') }}" class="btn-primary">+ Book Appointment</a>
</div>
<div class="card overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Reference</th><th class="p-3">Purpose</th><th class="p-3">Scheduled</th><th class="p-3">Status</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($items as $a)
                <tr>
                    <td class="p-3 font-mono text-xs">{{ $a->reference_no }}</td>
                    <td class="p-3">{{ $a->purpose }}</td>
                    <td class="p-3">{{ $a->scheduled_at->format('M d, Y · h:i A') }}</td>
                    <td class="p-3"><x-status-badge :status="$a->status"/></td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-6 text-center text-slate-500">No appointments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
</x-layouts.app>
