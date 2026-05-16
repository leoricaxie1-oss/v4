<x-layouts.app>
<h1 class="text-2xl font-bold">Approve residents</h1>
<p class="text-sm text-slate-500">Step 2 of 3 — these residents have already been verified by the Secretary.</p>
<div class="card mt-6 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Purok</th><th class="p-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($residents as $u)
                <tr>
                    <td class="p-3 font-medium">{{ $u->full_name }}</td>
                    <td class="p-3">{{ $u->email }}</td>
                    <td class="p-3">{{ $u->resident?->purok?->name ?? '—' }}</td>
                    <td class="p-3 text-right">
                        <form method="POST" action="{{ route('kagawad.residents.approve', $u) }}">@csrf
                            <button class="btn-success">Approve &amp; Forward to Captain</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-6 text-center text-slate-500">No residents at this stage.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $residents->links() }}</div>
</x-layouts.app>
