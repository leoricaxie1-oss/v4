@php $u = auth()->user(); @endphp
<header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b bg-white px-4 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
        <button type="button" class="md:hidden rounded-md p-2 text-slate-500 hover:bg-slate-100">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <p class="text-sm font-medium text-slate-500">Welcome back, <span class="text-slate-900">{{ $u->first_name }}</span></p>
    </div>

    <div class="flex items-center gap-3">
        @php $unread = $u->notifications()->whereNull('read_at')->count(); @endphp
        <a href="{{ $u->hasRole('resident') ? route('resident.notifications.index') : '#' }}"
           class="relative rounded-full p-2 text-slate-500 hover:bg-slate-100">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0"/></svg>
            @if ($unread > 0)
                <span class="absolute -right-0.5 -top-0.5 grid h-4 w-4 place-items-center rounded-full bg-rose-600 text-[10px] font-semibold text-white">{{ $unread }}</span>
            @endif
        </a>
        <div x-data="{open:false}" class="relative">
            <button @click="open=!open" class="flex items-center gap-2 rounded-full bg-slate-100 px-2 py-1 text-sm font-medium hover:bg-slate-200">
                <span class="grid h-7 w-7 place-items-center rounded-full bg-brand-600 text-xs font-semibold text-white">{{ $u->initials }}</span>
                <span class="hidden sm:inline">{{ $u->first_name }}</span>
            </button>
            <div x-show="open" @click.away="open=false" class="absolute right-0 mt-2 w-48 overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-slate-200" x-cloak>
                @if ($u->hasRole('resident'))
                    <a href="{{ route('resident.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Profile</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">@csrf
                    <button class="block w-full px-4 py-2 text-left text-sm text-rose-600 hover:bg-rose-50">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</header>
