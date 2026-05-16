@php
    $u = auth()->user();
    $role = match (true) {
        $u->hasRole('admin')     => 'admin',
        $u->hasRole('captain')   => 'captain',
        $u->hasRole('kagawad')   => 'kagawad',
        $u->hasRole('secretary') => 'secretary',
        $u->hasRole('tanod')     => 'tanod',
        default                  => 'resident',
    };
@endphp
<aside x-data="{open:false}" class="bg-white border-r border-slate-200 hidden md:flex md:flex-col md:w-64">
    <div class="flex items-center gap-3 px-4 py-4 border-b">
        <a href="{{ route('home') }}" class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">P1</a>
        <div class="flex flex-col leading-tight">
            <span class="font-semibold text-slate-900">PanipOne</span>
            <span class="text-xs uppercase tracking-wider text-slate-500">{{ $role }}</span>
        </div>
    </div>
    <nav class="flex-1 space-y-1 overflow-y-auto p-3 text-sm">
        @if ($role === 'resident')
            <a href="{{ route('resident.dashboard') }}"    class="sidebar-link {{ request()->routeIs('resident.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('resident.documents.index') }}"   class="sidebar-link {{ request()->routeIs('resident.documents.*') ? 'active' : '' }}">Document Requests</a>
            <a href="{{ route('resident.complaints.index') }}"  class="sidebar-link {{ request()->routeIs('resident.complaints.*') ? 'active' : '' }}">Complaints &amp; Blotter</a>
            <a href="{{ route('resident.skills.index') }}"      class="sidebar-link {{ request()->routeIs('resident.skills.*') ? 'active' : '' }}">My Skills/Services</a>
            <a href="{{ route('resident.appointments.index') }}"class="sidebar-link {{ request()->routeIs('resident.appointments.*') ? 'active' : '' }}">Appointments</a>
            <a href="{{ route('resident.messages.index') }}"    class="sidebar-link {{ request()->routeIs('resident.messages.*') ? 'active' : '' }}">Messages</a>
            <a href="{{ route('resident.notifications.index') }}" class="sidebar-link {{ request()->routeIs('resident.notifications.*') ? 'active' : '' }}">Notifications</a>
            <a href="{{ route('resident.profile.edit') }}"      class="sidebar-link {{ request()->routeIs('resident.profile.*') ? 'active' : '' }}">My Profile</a>
        @elseif ($role === 'secretary')
            <a href="{{ route('secretary.dashboard') }}" class="sidebar-link {{ request()->routeIs('secretary.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('secretary.residents') }}" class="sidebar-link {{ request()->routeIs('secretary.residents') ? 'active' : '' }}">Verify Residents</a>
            <a href="{{ route('secretary.documents') }}" class="sidebar-link {{ request()->routeIs('secretary.documents') ? 'active' : '' }}">Documents</a>
            <a href="{{ route('secretary.skills') }}"    class="sidebar-link {{ request()->routeIs('secretary.skills') ? 'active' : '' }}">Skills/Services</a>
            <a href="{{ route('secretary.businesses') }}" class="sidebar-link {{ request()->routeIs('secretary.businesses') ? 'active' : '' }}">Businesses</a>
        @elseif ($role === 'kagawad')
            <a href="{{ route('kagawad.dashboard') }}" class="sidebar-link {{ request()->routeIs('kagawad.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('kagawad.residents') }}" class="sidebar-link">Approve Residents</a>
            <a href="{{ route('kagawad.skills') }}"    class="sidebar-link">Skills/Services</a>
            <a href="{{ route('kagawad.businesses') }}" class="sidebar-link">Businesses</a>
            <a href="{{ route('analytics') }}"          class="sidebar-link">Analytics</a>
        @elseif ($role === 'captain')
            <a href="{{ route('captain.dashboard') }}" class="sidebar-link {{ request()->routeIs('captain.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('captain.residents') }}" class="sidebar-link">Approve Residents</a>
            <a href="{{ route('captain.skills') }}"    class="sidebar-link">Skills/Services</a>
            <a href="{{ route('captain.businesses') }}" class="sidebar-link">Businesses</a>
            <a href="{{ route('analytics') }}"          class="sidebar-link">Analytics</a>
        @elseif ($role === 'tanod')
            <a href="{{ route('tanod.dashboard') }}"  class="sidebar-link {{ request()->routeIs('tanod.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('tanod.complaints') }}" class="sidebar-link">Complaints</a>
            <a href="{{ route('tanod.blotter') }}"    class="sidebar-link">Blotter</a>
        @elseif ($role === 'admin')
            <a href="{{ route('admin.dashboard') }}"     class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.users') }}"         class="sidebar-link">User Management</a>
            <a href="{{ route('admin.roles') }}"         class="sidebar-link">Roles &amp; Permissions</a>
            <a href="{{ route('admin.announcements.index') }}" class="sidebar-link">Announcements</a>
            <a href="{{ route('admin.activity') }}"      class="sidebar-link">Activity Logs</a>
            <a href="{{ route('admin.audit') }}"         class="sidebar-link">Audit Logs</a>
            <a href="{{ route('analytics') }}"           class="sidebar-link">Analytics</a>
        @endif
    </nav>
    <div class="p-3 border-t">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button class="sidebar-link w-full text-left text-rose-600 hover:bg-rose-50">Sign out</button>
        </form>
    </div>
</aside>
