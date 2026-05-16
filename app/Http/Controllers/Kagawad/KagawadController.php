<?php

namespace App\Http\Controllers\Kagawad;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Complaint;
use App\Models\SkillService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KagawadController extends Controller
{
    public function dashboard(): View
    {
        return view('kagawad.dashboard', [
            'pending_residents' => User::where('account_status', 'pending')
                ->where('approved_by_secretary', true)
                ->where('approved_by_kagawad', false)->count(),
            'pending_skills'    => SkillService::where('approved_by_secretary', true)
                ->where('approved_by_kagawad', false)->where('status', 'pending')->count(),
            'pending_business'  => Business::where('approved_by_secretary', true)
                ->where('approved_by_kagawad', false)->where('status', 'pending')->count(),
            'recent_complaints' => Complaint::with('complainant')->latest()->limit(5)->get(),
        ]);
    }

    public function residents(): View
    {
        $residents = User::role('resident')
            ->where('approved_by_secretary', true)
            ->where('approved_by_kagawad', false)
            ->where('account_status', 'pending')
            ->with('resident.purok')
            ->latest()->paginate(15);
        return view('kagawad.residents', compact('residents'));
    }

    public function approveResident(User $user): RedirectResponse
    {
        abort_unless($user->approved_by_secretary, 422, 'Awaiting secretary verification.');
        $user->forceFill([
            'approved_by_kagawad'    => true,
            'approved_by_kagawad_id' => Auth::id(),
            'approved_by_kagawad_at' => now(),
        ])->save();
        return back()->with('toast', ['type' => 'success', 'message' => 'Forwarded to Captain.']);
    }

    public function skills(): View
    {
        $items = SkillService::with('user')
            ->where('status', 'pending')
            ->where('approved_by_secretary', true)
            ->where('approved_by_kagawad', false)
            ->latest()->paginate(15);
        return view('kagawad.skills', compact('items'));
    }

    public function approveSkill(SkillService $skill): RedirectResponse
    {
        abort_unless($skill->approved_by_secretary, 422, 'Awaiting secretary.');
        $skill->markApproved('kagawad', Auth::id());
        return back()->with('toast', ['type' => 'success', 'message' => 'Forwarded to Captain.']);
    }

    public function businesses(): View
    {
        $items = Business::with('owner')
            ->where('status', 'pending')
            ->where('approved_by_secretary', true)
            ->where('approved_by_kagawad', false)
            ->latest()->paginate(15);
        return view('kagawad.businesses', compact('items'));
    }

    public function approveBusiness(Business $business): RedirectResponse
    {
        abort_unless($business->approved_by_secretary, 422, 'Awaiting secretary.');
        $business->markApproved('kagawad', Auth::id());
        return back()->with('toast', ['type' => 'success', 'message' => 'Forwarded to Captain.']);
    }
}
