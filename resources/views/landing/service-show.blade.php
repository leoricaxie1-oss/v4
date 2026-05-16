<x-layouts.public>
<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <a href="{{ route('directory.index') }}" class="text-sm text-brand-600 hover:underline">← Back to directory</a>
    <div class="card mt-4"><div class="card-body">
        <span class="badge-success">{{ $service->display_category }}</span>
        <h1 class="mt-2 text-2xl font-bold">{{ $service->title }}</h1>
        <p class="mt-2 text-sm text-slate-500">Offered by {{ $service->user->full_name }}</p>
        <p class="mt-4 whitespace-pre-line text-slate-700">{{ $service->description }}</p>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">Rate</dt>
                <dd>{{ $service->rate ? '₱'.number_format($service->rate, 2) . ' ' . $service->rate_unit : 'Negotiable' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase text-slate-500">Contact</dt>
                <dd>{{ $service->contact_phone }} · {{ $service->contact_email }}</dd>
            </div>
        </dl>

        @auth
            <a href="{{ route('resident.messages.index') }}" class="btn-primary mt-6">Send inquiry</a>
        @else
            <a href="{{ route('login') }}" class="btn-primary mt-6">Login to inquire</a>
        @endauth
    </div></div>

    <div class="card mt-6"><div class="card-body">
        <h2 class="text-lg font-semibold">Reviews</h2>
        @forelse ($service->reviews as $r)
            <div class="mt-4 border-t pt-4">
                <p class="text-sm font-medium">{{ $r->user->full_name }} <span class="text-amber-500">{{ str_repeat('★', $r->rating) }}</span></p>
                <p class="mt-1 text-sm text-slate-600">{{ $r->comment }}</p>
            </div>
        @empty
            <p class="mt-3 text-sm text-slate-500">No reviews yet.</p>
        @endforelse
    </div></div>
</section>
</x-layouts.public>
