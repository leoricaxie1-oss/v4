<x-layouts.app>
<h1 class="text-2xl font-bold">Audit logs</h1>
<div class="card mt-6 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Time</th><th class="p-3">User</th><th class="p-3">Event</th><th class="p-3">Target</th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($logs as $l)
                <tr>
                    <td class="p-3 text-xs">{{ $l->created_at->format('Y-m-d H:i') }}</td>
                    <td class="p-3">{{ $l->user?->email ?? 'system' }}</td>
                    <td class="p-3">{{ $l->event }}</td>
                    <td class="p-3">{{ class_basename($l->auditable_type) }} #{{ $l->auditable_id }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $logs->links() }}</div>
</x-layouts.app>
