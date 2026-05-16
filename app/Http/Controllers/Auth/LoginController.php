<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\ActivityLog;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember    = (bool) $request->boolean('remember');

        $user = User::where('email', strtolower($credentials['email']))->first();

        if (! $user) {
            $this->logAttempt(null, $credentials['email'], 'failed');
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        if ($user->isLocked()) {
            $this->logAttempt($user->id, $user->email, 'locked');
            throw ValidationException::withMessages([
                'email' => 'Account is temporarily locked. Try again after '.$user->locked_until->diffForHumans().'.',
            ]);
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            $user->increment('failed_login_attempts');

            $max = (int) config('auth.lockout.max_attempts', 5);
            if ($user->failed_login_attempts >= $max) {
                $user->locked_until = now()->addMinutes((int) config('auth.lockout.decay_minutes', 15));
                $user->save();
            }

            $this->logAttempt($user->id, $user->email, 'failed');
            throw ValidationException::withMessages(['email' => 'Invalid credentials.']);
        }

        if ($user->account_status !== 'approved') {
            $stage = $user->approved_by_captain  ? 'captain'
                  : ($user->approved_by_kagawad  ? 'captain'
                  : ($user->approved_by_secretary? 'kagawad' : 'secretary'));

            throw ValidationException::withMessages([
                'email' => "Account is still pending. Awaiting approval by the {$stage}.",
            ]);
        }

        if ($user->account_status === 'suspended') {
            throw ValidationException::withMessages(['email' => 'Account is suspended. Please contact the barangay.']);
        }

        // Successful login
        Auth::login($user, $remember);
        $user->forceFill([
            'failed_login_attempts' => 0,
            'locked_until'          => null,
            'last_login_at'         => now(),
            'last_login_ip'         => $request->ip(),
        ])->save();

        $request->session()->regenerate();
        $this->logAttempt($user->id, $user->email, 'success');
        ActivityLog::record('login', 'auth', "User {$user->email} logged in.");

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        $email = Auth::user()?->email;
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        ActivityLog::record('logout', 'auth', "User {$email} logged out.");
        return redirect()->route('home')->with('toast', ['type' => 'info', 'message' => 'You have been signed out.']);
    }

    private function logAttempt(?int $userId, string $email, string $result): void
    {
        LoginHistory::create([
            'user_id'   => $userId,
            'email'     => $email,
            'ip_address'=> request()->ip(),
            'user_agent'=> request()->userAgent(),
            'result'    => $result,
        ]);
    }
}
