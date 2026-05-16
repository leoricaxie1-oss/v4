<x-layouts.public>
<section class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <a href="{{ route('directory.index', ['type'=>'businesses']) }}" class="text-sm text-brand-600 hover:underline">← Back to businesses</a>
    <div class="card mt-4"><div class="card-body">
        <span class="badge-info">{{ $business->business_type }}</span>
        <h1 class="mt-2 text-2xl font-bold">{{ $business->business_name }}</h1>
        <p class="mt-2 text-sm text-slate-500">Owned by {{ $business->owner->full_name }}</p>
        <p class="mt-4 whitespace-pre-line text-slate-700">{{ $business->description }}</p>

        <dl class="mt-6 grid gap-4 sm:grid-cols-2">
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Address</dt><dd>{{ $business->address }}</dd></div>
            <div><dt class="text-xs font-semibold uppercase text-slate-500">Contact</dt><dd>{{ $business->contact_phone }} · {{ $business->contact_email }}</dd></div>
        </dl>
    </div></div>
</section>
</x-layouts.public>
