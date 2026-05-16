<x-layouts.public>

{{-- Hero --}}
<section class="bg-gradient-to-br from-brand-700 via-brand-600 to-brand-500 text-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
        <p class="mb-3 inline-block rounded-full bg-white/15 px-3 py-1 text-xs font-medium uppercase tracking-wider">
            {{ config('panipone.barangay.name') }} — {{ config('panipone.barangay.municipality') }}
        </p>
        <h1 class="max-w-3xl text-3xl font-bold leading-tight sm:text-5xl">
            Your barangay, one click away.
        </h1>
        <p class="mt-4 max-w-2xl text-lg text-white/90">
            Request documents, file complaints, find trusted neighborhood services and stay informed —
            all from a single, secure online portal.
        </p>

        <form action="{{ route('home') }}" method="GET" class="mt-8 flex max-w-2xl items-center gap-2 rounded-full bg-white p-2 shadow-lg">
            <input type="text" name="q" value="{{ $search }}" placeholder="Search for plumbers, sari-sari stores, services…"
                   class="form-input flex-1 border-0 bg-transparent text-slate-800 placeholder:text-slate-400 focus:ring-0">
            <button class="btn-primary rounded-full">Search</button>
        </form>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <a href="{{ route('register') }}" class="rounded-xl bg-white/10 p-4 backdrop-blur hover:bg-white/15">
                <p class="text-sm font-medium">Register as Resident</p>
                <p class="mt-1 text-xs text-white/70">Approval in three quick steps.</p>
            </a>
            <a href="{{ route('directory.index') }}" class="rounded-xl bg-white/10 p-4 backdrop-blur hover:bg-white/15">
                <p class="text-sm font-medium">Browse Services</p>
                <p class="mt-1 text-xs text-white/70">Local providers, verified.</p>
            </a>
            <a href="{{ route('login') }}" class="rounded-xl bg-white/10 p-4 backdrop-blur hover:bg-white/15">
                <p class="text-sm font-medium">Request a Document</p>
                <p class="mt-1 text-xs text-white/70">Clearance, residency &amp; more.</p>
            </a>
            <a href="{{ route('login') }}" class="rounded-xl bg-white/10 p-4 backdrop-blur hover:bg-white/15">
                <p class="text-sm font-medium">File a Complaint</p>
                <p class="mt-1 text-xs text-white/70">Online, tracked, mediated.</p>
            </a>
        </div>
    </div>
</section>

{{-- Announcements --}}
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-end justify-between">
        <h2 class="text-2xl font-bold">Latest Announcements</h2>
    </div>
    <div class="grid gap-5 md:grid-cols-3">
        @forelse ($announcements as $a)
            <article class="card">
                <div class="card-body">
                    <span class="badge-info uppercase">{{ $a->priority }}</span>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">{{ $a->title }}</h3>
                    <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit(strip_tags($a->body), 140) }}</p>
                    <p class="mt-3 text-xs text-slate-400">{{ $a->published_at?->diffForHumans() }}</p>
                </div>
            </article>
        @empty
            <p class="text-sm text-slate-500">No announcements yet.</p>
        @endforelse
    </div>
</section>

{{-- Featured services --}}
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-6 flex items-end justify-between">
            <h2 class="text-2xl font-bold">Approved Community Skills</h2>
            <a href="{{ route('directory.index') }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">Browse all →</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($skills as $s)
                <a href="{{ route('directory.service', $s) }}" class="card group transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="card-body">
                        <span class="badge-success">{{ $s->display_category }}</span>
                        <h3 class="mt-2 line-clamp-1 font-semibold text-slate-900 group-hover:text-brand-700">{{ $s->title }}</h3>
                        <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $s->description }}</p>
                        <p class="mt-3 text-xs text-slate-400">★ {{ number_format($s->rating_avg, 1) }} · {{ $s->views_count }} views</p>
                    </div>
                </a>
            @empty
                <p class="col-span-full text-sm text-slate-500">No approved services yet.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- Businesses --}}
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-6 flex items-end justify-between">
        <h2 class="text-2xl font-bold">Local Businesses</h2>
        <a href="{{ route('directory.index', ['type'=>'businesses']) }}" class="text-sm font-medium text-brand-600 hover:text-brand-700">View all →</a>
    </div>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @forelse ($businesses as $b)
            <a href="{{ route('directory.business', $b) }}" class="card group transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="card-body">
                    <span class="badge-info">{{ $b->business_type }}</span>
                    <h3 class="mt-2 line-clamp-1 font-semibold text-slate-900 group-hover:text-brand-700">{{ $b->business_name }}</h3>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-600">{{ $b->description }}</p>
                </div>
            </a>
        @empty
            <p class="col-span-full text-sm text-slate-500">No approved businesses yet.</p>
        @endforelse
    </div>
</section>

{{-- Emergency banner --}}
<section class="bg-rose-600 text-white">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 sm:flex-row sm:px-6 lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wider">Emergency Hotline</p>
            <p class="text-2xl font-bold">{{ config('panipone.barangay.hotline') }}</p>
        </div>
        <a href="{{ route('contact') }}" class="rounded-full bg-white px-5 py-2 text-sm font-semibold text-rose-700 hover:bg-rose-50">More contacts →</a>
    </div>
</section>

</x-layouts.public>
