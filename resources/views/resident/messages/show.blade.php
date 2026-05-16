<x-layouts.app>
<div class="mx-auto max-w-3xl">
    <a href="{{ route('resident.messages.index') }}" class="text-sm text-brand-600 hover:underline">← Conversations</a>
    <h1 class="mt-2 text-xl font-semibold">{{ $conversation->subject ?: 'Conversation' }}</h1>

    <div class="card mt-4"><div class="card-body space-y-4">
        @foreach ($messages as $m)
            @php $mine = $m->sender_id === auth()->id(); @endphp
            <div class="flex {{ $mine ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-sm rounded-lg px-3 py-2 text-sm {{ $mine ? 'bg-brand-600 text-white' : 'bg-slate-100 text-slate-900' }}">
                    <p class="whitespace-pre-line">{{ $m->body }}</p>
                    <p class="mt-1 text-[10px] opacity-70">{{ $m->sender->first_name }} · {{ $m->created_at->diffForHumans() }}</p>
                </div>
            </div>
        @endforeach
    </div></div>

    <form method="POST" action="{{ route('resident.messages.send', $conversation) }}" enctype="multipart/form-data" class="mt-4">
        @csrf
        <x-textarea name="body" rows="3" required placeholder="Write a message…" />
        <input type="file" name="attachment" class="form-input">
        <div class="mt-2 flex justify-end"><button class="btn-primary">Send</button></div>
    </form>
</div>
</x-layouts.app>
