<x-layouts.app>
<div class="mb-4 flex items-center justify-between">
    <h1 class="text-2xl font-bold">Notifications</h1>
    <form method="POST" action="{{ route('resident.notifications.read-all') }}">@csrf
        <button class="btn-secondary">Mark all as read</button>
    </form>
</div>

<div class="card overflow-hidden">
    <ul class="divide-y">
        @forelse ($items as $n)
            <li class="flex items-center justify-between p-4 {{ $n->read_at ? '' : 'bg-brand-50' }}">
                <div>
                    <p class="text-sm">{{ data_get($n->data, 'message', $n->type) }}</p>
                    <p class="text-xs text-slate-500">{{ $n->created_at->diffForHumans() }}</p>
                </div>
                @if (! $n->read_at)
                    <form method="POST" action="{{ route('resident.notifications.read', $n->id) }}">@csrf
                        <button class="text-xs font-medium text-brand-600 hover:underline">Mark read</button>
                    </form>
                @endif
            </li>
        @empty
            <li class="p-6 text-center text-slate-500">No notifications.</li>
        @endforelse
    </ul>
</div>
<div class="mt-4">{{ $items->links() }}</div>
</x-layouts.app>
