<x-layouts.public>
<section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold">
            {{ $type === 'businesses' ? 'Local Businesses' : 'Skills &amp; Services' }}
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('directory.index', ['type'=>'services']) }}"
               class="{{ $type === 'services' ? 'btn-primary' : 'btn-secondary' }}">Services</a>
            <a href="{{ route('directory.index', ['type'=>'businesses']) }}"
               class="{{ $type === 'businesses' ? 'btn-primary' : 'btn-secondary' }}">Businesses</a>
        </div>
    </div>

    <form action="{{ route('directory.index') }}" method="GET" class="mb-6 grid gap-3 sm:grid-cols-3 lg:grid-cols-4">
        <input type="hidden" name="type" value="{{ $type }}">
        <input type="text" name="q" value="{{ $q }}" placeholder="Search…" class="form-input sm:col-span-2">
        @if ($type === 'services')
            <select name="category" class="form-input">
                <option value="">All categories</option>
                @foreach ($categories as $c)
                    <option value="{{ $c }}" @selected($category === $c)>{{ $c }}</option>
                @endforeach
            </select>
        @endif
        <button class="btn-primary">Apply filters</button>
    </form>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($items as $item)
            @if ($type === 'businesses')
                <a href="{{ route('directory.business', $item) }}" class="card hover:shadow-md transition">
                    <div class="card-body">
                        <span class="badge-info">{{ $item->business_type }}</span>
                        <h3 class="mt-2 font-semibold">{{ $item->business_name }}</h3>
                        <p class="mt-1 line-clamp-3 text-sm text-slate-600">{{ $item->description }}</p>
                        <p class="mt-3 text-xs text-slate-400">{{ $item->address }}</p>
                    </div>
                </a>
            @else
                <a href="{{ route('directory.service', $item) }}" class="card hover:shadow-md transition">
                    <div class="card-body">
                        <span class="badge-success">{{ $item->display_category }}</span>
                        <h3 class="mt-2 font-semibold">{{ $item->title }}</h3>
                        <p class="mt-1 line-clamp-3 text-sm text-slate-600">{{ $item->description }}</p>
                        <p class="mt-3 text-xs text-slate-400">★ {{ number_format($item->rating_avg, 1) }} — {{ $item->user->full_name }}</p>
                    </div>
                </a>
            @endif
        @empty
            <p class="col-span-full text-sm text-slate-500">No results found.</p>
        @endforelse
    </div>

    <div class="mt-8">{{ $items->links() }}</div>
</section>
</x-layouts.public>
