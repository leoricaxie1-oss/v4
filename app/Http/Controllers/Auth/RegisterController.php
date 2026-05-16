<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\ActivityLog;
use App\Models\Province;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showForm(): View
    {
        return view('auth.register', [
            'provinces' => Province::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'first_name'      => $data['first_name'],
                'middle_name'     => $data['middle_name'] ?? null,
                'last_name'       => $data['last_name'],
                'suffix'          => $data['suffix'] ?? null,
                'email'           => strtolower($data['email']),
                'phone'           => $data['phone'],
                'password'        => Hash::make($data['password']),
                'account_status'  => 'pending',
            ]);

            $user->assignRole('resident');

            Resident::create([
                'user_id'     => $user->id,
                'province_id' => $data['province_id'],
                'city_id'     => $data['city_id'],
                'barangay_id' => $data['barangay_id'],
                'purok_id'    => $data['purok_id'],
            ]);

            ActivityLog::record('register', 'auth', "Resident registration created (pending approval) for {$user->email}", [
                'user_id' => $user->id,
            ]);

            $user->sendEmailVerificationNotification();
        });

        return redirect()
            ->route('login')
            ->with('toast', [
                'type'    => 'success',
                'title'   => 'Registration submitted',
                'message' => 'Your account is pending approval by the Secretary, Kagawad, and Captain. '.
                             'You will receive an email once approved.',
            ]);
    }
}
