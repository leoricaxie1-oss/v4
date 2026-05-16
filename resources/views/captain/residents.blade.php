<x-layouts.app>
<h1 class="text-2xl font-bold">Final approval — residents</h1>
<p class="text-sm text-slate-500">Step 3 of 3 — Secretary and Kagawad have already approved.</p>

<div class="card mt-6 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Purok</th><th class="p-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($residents as $u)
                <tr>
                    <td class="p-3">{{ $u->full_name }}</td>
                    <td class="p-3">{{ $u->email }}</td>
                    <td class="p-3">{{ $u->resident?->purok?->name ?? '—' }}</td>
                    <td class="p-3 text-right">
                        <form method="POST" action="{{ route('captain.residents.approve', $u) }}">@csrf
                            <button class="btn-success">Approve account</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-6 text-center text-slate-500">No residents awaiting final approval.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $residents->links() }}</div>
</x-layouts.app>
