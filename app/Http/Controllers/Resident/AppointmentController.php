<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $items = Appointment::where('user_id', Auth::id())->latest('scheduled_at')->paginate(10);
        return view('resident.appointments.index', compact('items'));
    }

    public function create(): View
    {
        return view('resident.appointments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'purpose'      => ['required', 'string', 'max:255'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'notes'        => ['nullable', 'string', 'max:500'],
        ]);

        $appointment = Appointment::create([
            'user_id'      => Auth::id(),
            'purpose'      => $data['purpose'],
            'scheduled_at' => $data['scheduled_at'],
            'notes'        => $data['notes'] ?? null,
            'status'       => 'requested',
        ]);

        return redirect()->route('resident.appointments.index')->with('toast', [
            'type' => 'success', 'message' => "Appointment {$appointment->reference_no} requested.",
        ]);
    }
}
