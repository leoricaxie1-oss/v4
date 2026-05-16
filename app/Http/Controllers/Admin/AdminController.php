<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\AuditLog;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'users_count'        => User::count(),
            'pending_residents'  => User::where('account_status', 'pending')->count(),
            'recent_activity'    => ActivityLog::with('user')->latest()->limit(10)->get(),
            'recent_logins'      => LoginHistory::with('user')->latest()->limit(10)->get(),
        ]);
    }

    public function users(Request $request): View
    {
        $q = $request->query('q');
        $users = User::with('roles')
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('email', 'like', "%{$q}%")
                  ->orWhere('first_name', 'like', "%{$q}%")
                  ->orWhere('last_name', 'like', "%{$q}%");
            }))
            ->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function assignRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['role' => ['required', 'exists:roles,name']]);
        $user->syncRoles([$data['role']]);
        return back()->with('toast', ['type' => 'success', 'message' => "Role {$data['role']} assigned to {$user->email}."]);
    }

    public function toggleSuspend(User $user): RedirectResponse
    {
        $user->update(['account_status' => $user->account_status === 'suspended' ? 'approved' : 'suspended']);
        return back()->with('toast', ['type' => 'info', 'message' => 'User status toggled.']);
    }

    public function roles(): View
    {
        return view('admin.roles', ['roles' => Role::with('permissions')->get()]);
    }

    public function activityLogs(): View
    {
        $logs = ActivityLog::with('user')->latest()->paginate(50);
        return view('admin.activity-logs', compact('logs'));
    }

    public function auditLogs(): View
    {
        $logs = AuditLog::with('user')->latest()->paginate(50);
        return view('admin.audit-logs', compact('logs'));
    }
}
