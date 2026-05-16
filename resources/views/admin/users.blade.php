<x-layouts.app>
<h1 class="text-2xl font-bold">User management</h1>
<form method="GET" class="my-4 max-w-md">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search users…" class="form-input">
</form>
<div class="card overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200 text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-500">
            <tr><th class="p-3">Name</th><th class="p-3">Email</th><th class="p-3">Role</th><th class="p-3">Status</th><th class="p-3"></th></tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($users as $u)
                <tr>
                    <td class="p-3">{{ $u->full_name }}</td>
                    <td class="p-3">{{ $u->email }}</td>
                    <td class="p-3">{{ $u->roles->pluck('name')->join(', ') ?: '—' }}</td>
                    <td class="p-3"><x-status-badge :status="$u->account_status"/></td>
                    <td class="p-3 text-right space-x-1">
                        <form method="POST" action="{{ route('admin.users.role', $u) }}" class="inline">
                            @csrf
                            <select name="role" class="form-input inline-block w-auto py-1">
                                @foreach (['admin','captain','kagawad','secretary','tanod','resident'] as $r)
                                    <option value="{{ $r }}" @selected($u->hasRole($r))>{{ $r }}</option>
                                @endforeach
                            </select>
                            <button class="btn-secondary">Set</button>
                        </form>
                        <form method="POST" action="{{ route('admin.users.suspend', $u) }}" class="inline">@csrf
                            <button class="{{ $u->account_status === 'suspended' ? 'btn-success' : 'btn-danger' }}">
                                {{ $u->account_status === 'suspended' ? 'Reactivate' : 'Suspend' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $users->links() }}</div>
</x-layouts.app>
