<x-layouts.app>
<h1 class="text-2xl font-bold">Skills/Services — Kagawad review</h1>
<div class="card mt-6 overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Title</th><th class="p-3">Category</th><th class="p-3">Owner</th><th class="p-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($items as $s)
                <tr>
                    <td class="p-3 font-medium">{{ $s->title }}</td>
                    <td class="p-3">{{ $s->display_category }}</td>
                    <td class="p-3">{{ $s->user->full_name }}</td>
                    <td class="p-3 text-right">
                        <form method="POST" action="{{ route('kagawad.skills.approve', $s) }}">@csrf
                            <button class="btn-success">Forward to Captain</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-6 text-center text-slate-500">Nothing pending.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $items->links() }}</div>
</x-layouts.app>
