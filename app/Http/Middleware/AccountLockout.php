<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccountLockout
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->isLocked()) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')->withErrors([
                'email' => 'Account is locked. Please try again later.',
            ]);
        }
        return $next($request);
    }
}
