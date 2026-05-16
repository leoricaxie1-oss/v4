<x-layouts.app>
<h1 class="text-2xl font-bold">Roles &amp; Permissions</h1>
<div class="mt-6 grid gap-4 md:grid-cols-2 lg:grid-cols-3">
    @foreach ($roles as $r)
        <div class="card"><div class="card-body">
            <h3 class="font-semibold capitalize">{{ $r->name }}</h3>
            <ul class="mt-2 flex flex-wrap gap-1 text-xs">
                @foreach ($r->permissions as $p)
                    <li class="rounded bg-slate-100 px-2 py-0.5">{{ $p->name }}</li>
                @endforeach
            </ul>
        </div></div>
    @endforeach
</div>
</x-layouts.app>
