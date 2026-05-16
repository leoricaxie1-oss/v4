<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        $u = Auth::user();
        return match (true) {
            $u->hasRole('admin')     => redirect()->route('admin.dashboard'),
            $u->hasRole('captain')   => redirect()->route('captain.dashboard'),
            $u->hasRole('kagawad')   => redirect()->route('kagawad.dashboard'),
            $u->hasRole('secretary') => redirect()->route('secretary.dashboard'),
            $u->hasRole('tanod')     => redirect()->route('tanod.dashboard'),
            default                  => redirect()->route('resident.dashboard'),
        };
    }

    public function pending(): \Illuminate\View\View
    {
        return view('auth.pending', [
            'user' => Auth::user(),
        ]);
    }
}
