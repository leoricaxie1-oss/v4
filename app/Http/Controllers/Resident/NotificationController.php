<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $items = Auth::user()->notifications()->latest()->paginate(20);
        return view('resident.notifications.index', compact('items'));
    }

    public function read(Request $request, string $id): RedirectResponse
    {
        Auth::user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return back();
    }

    public function readAll(): RedirectResponse
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('toast', ['type' => 'success', 'message' => 'All marked as read.']);
    }
}
