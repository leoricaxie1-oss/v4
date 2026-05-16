<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\Resident;
use App\Models\SkillService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CaptainController extends Controller
{
    public function dashboard(): View
    {
        return view('captain.dashboard', [
            'total_residents'   => Resident::count(),
            'pending_residents' => User::where('account_status', 'pending')
                ->where('approved_by_kagawad', true)
                ->where('approved_by_captain', false)->count(),
            'unresolved_complaints' => Complaint::whereNotIn('status', ['resolved', 'dismissed'])->count(),
            'monthly_documents' => Document::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)->count(),
            'escalated' => Complaint::where('status', 'escalated')->latest()->limit(5)->get(),
        ]);
    }

    public function residents(): View
    {
        $residents = User::role('resident')
            ->where('approved_by_secretary', true)
            ->where('approved_by_kagawad', true)
            ->where('approved_by_captain', false)
            ->where('account_status', 'pending')
            ->with('resident.purok')
            ->latest()->paginate(15);
        return view('captain.residents', compact('residents'));
    }

    public function approveResident(User $user): RedirectResponse
    {
        abort_unless($user->approved_by_kagawad, 422, 'Awaiting kagawad approval.');
        $user->forceFill([
            'approved_by_captain'    => true,
            'approved_by_captain_id' => Auth::id(),
            'approved_by_captain_at' => now(),
            'account_status'         => 'approved',
        ])->save();

        // TODO: dispatch welcome notification (email + sms)
        return back()->with('toast', ['type' => 'success', 'message' => 'Resident account fully approved.']);
    }

    public function skills(): View
    {
        $items = SkillService::with('user')
            ->where('approved_by_kagawad', true)
            ->where('approved_by_captain', false)
            ->where('status', 'pending')
            ->latest()->paginate(15);
        return view('captain.skills', compact('items'));
    }

    public function approveSkill(SkillService $skill): RedirectResponse
    {
        abort_unless($skill->approved_by_kagawad, 422, 'Awaiting kagawad.');
        $skill->markApproved('captain', Auth::id());
        return back()->with('toast', ['type' => 'success', 'message' => 'Skill/service fully approved and now public.']);
    }

    public function businesses(): View
    {
        $items = Business::with('owner')
            ->where('approved_by_kagawad', true)
            ->where('approved_by_captain', false)
            ->where('status', 'pending')
            ->latest()->paginate(15);
        return view('captain.businesses', compact('items'));
    }

    public function approveBusiness(Business $business): RedirectResponse
    {
        abort_unless($business->approved_by_kagawad, 422, 'Awaiting kagawad.');
        $business->markApproved('captain', Auth::id());
        return back()->with('toast', ['type' => 'success', 'message' => 'Business approved and listed.']);
    }
}
