<x-layouts.app>
<h1 class="text-2xl font-bold">Messages</h1>
<div class="card mt-4 overflow-hidden">
    <ul class="divide-y">
        @forelse ($conversations as $c)
            <li><a href="{{ route('resident.messages.show', $c) }}" class="flex items-center justify-between p-4 hover:bg-slate-50">
                <div>
                    <p class="font-semibold">{{ $c->subject ?: 'Conversation' }}</p>
                    <p class="text-xs text-slate-500">{{ $c->last_message_at?->diffForHumans() }}</p>
                </div>
                <span class="text-xs text-slate-400">{{ $c->participants->count() }} participants</span>
            </a></li>
        @empty
            <li class="p-6 text-center text-slate-500">No conversations yet.</li>
        @endforelse
    </ul>
</div>
<div class="mt-4">{{ $conversations->links() }}</div>
</x-layouts.app>
