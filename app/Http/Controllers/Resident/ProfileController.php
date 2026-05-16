<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Rules\PhilippineMobile;
use App\Rules\StrongPassword;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user()->load('resident');
        return view('resident.profile.edit', [
            'user'      => $user,
            'provinces' => Province::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:60'],
            'middle_name' => ['nullable', 'string', 'max:60'],
            'last_name'   => ['required', 'string', 'max:60'],
            'suffix'      => ['nullable', 'string', 'max:10'],
            'phone'       => ['required', new PhilippineMobile, 'unique:users,phone,'.$user->id],
            'avatar'      => ['nullable', 'image', 'max:5120'],

            'birthdate'        => ['nullable', 'date'],
            'sex'              => ['nullable', 'in:male,female,other'],
            'civil_status'     => ['nullable', 'in:single,married,widowed,separated,divorced'],
            'occupation'       => ['nullable', 'string', 'max:80'],
            'religion'         => ['nullable', 'string', 'max:80'],

            'emergency_contact_name'     => ['nullable', 'string', 'max:120'],
            'emergency_contact_phone'    => ['nullable', 'string', 'max:20'],
            'emergency_contact_relation' => ['nullable', 'string', 'max:50'],

            'purok_id'    => ['required', 'integer', 'exists:puroks,id'],
        ]);

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update(array_intersect_key($data, array_flip([
            'first_name', 'middle_name', 'last_name', 'suffix', 'phone', 'avatar_path',
        ])));

        $user->resident?->update([
            'birthdate'                  => $data['birthdate'] ?? null,
            'sex'                        => $data['sex'] ?? null,
            'civil_status'               => $data['civil_status'] ?? null,
            'occupation'                 => $data['occupation'] ?? null,
            'religion'                   => $data['religion'] ?? null,
            'emergency_contact_name'     => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone'    => $data['emergency_contact_phone'] ?? null,
            'emergency_contact_relation' => $data['emergency_contact_relation'] ?? null,
            'purok_id'                   => $data['purok_id'],
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Profile updated.']);
    }

    public function password(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', new StrongPassword],
        ]);

        $user->update(['password' => Hash::make($data['password'])]);

        return back()->with('toast', ['type' => 'success', 'message' => 'Password updated.']);
    }
}
