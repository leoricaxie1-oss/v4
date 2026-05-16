<x-layouts.app>
<div class="mx-auto max-w-2xl">
    <div class="card"><div class="card-body text-center">
        <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-full bg-amber-100 text-amber-700">⏳</div>
        <h1 class="text-2xl font-bold">Your account is pending approval</h1>
        <p class="mt-2 text-sm text-slate-500">
            Approval happens sequentially. You will be notified by email once each step is completed.
        </p>

        <ul class="mx-auto mt-6 max-w-md space-y-3 text-left text-sm">
            <li class="flex items-center gap-3">
                <span class="grid h-7 w-7 place-items-center rounded-full {{ $user->approved_by_secretary ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $user->approved_by_secretary ? '✓' : '1' }}
                </span>
                Secretary verification
                @if ($user->approved_by_secretary) <span class="badge-success ml-auto">Done</span> @endif
            </li>
            <li class="flex items-center gap-3">
                <span class="grid h-7 w-7 place-items-center rounded-full {{ $user->approved_by_kagawad ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $user->approved_by_kagawad ? '✓' : '2' }}
                </span>
                Kagawad approval
                @if ($user->approved_by_kagawad) <span class="badge-success ml-auto">Done</span> @endif
            </li>
            <li class="flex items-center gap-3">
                <span class="grid h-7 w-7 place-items-center rounded-full {{ $user->approved_by_captain ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                    {{ $user->approved_by_captain ? '✓' : '3' }}
                </span>
                Captain final approval
                @if ($user->approved_by_captain) <span class="badge-success ml-auto">Done</span> @endif
            </li>
        </ul>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button class="btn-secondary">Sign out</button>
        </form>
    </div></div>
</div>
</x-layouts.app>
