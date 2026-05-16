<?php

namespace App\Http\Controllers\Secretary;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Complaint;
use App\Models\Document;
use App\Models\SkillService;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SecretaryController extends Controller
{
    public function dashboard(): View
    {
        return view('secretary.dashboard', [
            'pending_residents' => User::where('account_status', 'pending')
                ->where('approved_by_secretary', false)->count(),
            'pending_documents' => Document::where('status', 'pending_review')->count(),
            'pending_skills'    => SkillService::where('status', 'pending')->where('approved_by_secretary', false)->count(),
            'pending_business'  => Business::where('status', 'pending')->where('approved_by_secretary', false)->count(),
            'recent_complaints' => Complaint::with('complainant')->latest()->limit(5)->get(),
        ]);
    }

    // ------------------------------------------------------------------
    // Resident verification
    // ------------------------------------------------------------------

    public function residents(Request $request): View
    {
        $q = $request->query('q');
        $residents = User::role('resident')
            ->where('approved_by_secretary', false)
            ->where('account_status', 'pending')
            ->when($q, fn ($qq) => $qq->where(function ($w) use ($q) {
                $w->where('email', 'like', "%{$q}%")
                  ->orWhere('first_name', 'like', "%{$q}%")
                  ->orWhere('last_name', 'like', "%{$q}%");
            }))
            ->with('resident.purok')
            ->latest()->paginate(15);

        return view('secretary.residents', compact('residents'));
    }

    public function verifyResident(User $user): RedirectResponse
    {
        $this->authorize('access-secretary');
        $user->forceFill([
            'approved_by_secretary'    => true,
            'approved_by_secretary_id' => Auth::id(),
            'approved_by_secretary_at' => now(),
        ])->save();

        return back()->with('toast', ['type' => 'success', 'message' => 'Resident verified. Next: Kagawad review.']);
    }

    public function rejectResident(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:500']]);
        $user->update(['account_status' => 'rejected', 'rejection_reason' => $data['reason']]);
        return back()->with('toast', ['type' => 'warning', 'message' => 'Resident registration rejected.']);
    }

    // ------------------------------------------------------------------
    // Document processing — set pickup date, schedule, requirements
    // ------------------------------------------------------------------

    public function documents(Request $request): View
    {
        $documents = Document::with('user')
            ->when($request->query('status'), fn ($q, $s) => $q->where('status', $s))
            ->latest()->paginate(15);

        return view('secretary.documents', compact('documents'));
    }

    public function approveDocument(Request $request, Document $document): RedirectResponse
    {
        $data = $request->validate([
            'pickup_date'        => ['required', 'date', 'after_or_equal:today'],
            'pickup_schedule'    => ['required', 'string', 'max:120'],
            'claim_requirements' => ['nullable', 'string', 'max:1000'],
        ]);

        $document->update([
            'status'             => 'ready_for_pickup',
            'pickup_date'        => $data['pickup_date'],
            'pickup_schedule'    => $data['pickup_schedule'],
            'claim_requirements' => $data['claim_requirements'] ?? null,
            'processed_by_id'    => Auth::id(),
            'processed_at'       => now(),
        ]);

        // TODO: dispatch Notification (database / mail / sms)
        return back()->with('toast', ['type' => 'success', 'message' => "Document {$document->reference_no} approved."]);
    }

    public function rejectDocument(Request $request, Document $document): RedirectResponse
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:500']]);
        $document->update([
            'status'           => 'rejected',
            'rejection_reason' => $data['reason'],
            'processed_by_id'  => Auth::id(),
            'processed_at'     => now(),
        ]);
        return back()->with('toast', ['type' => 'warning', 'message' => 'Document request rejected.']);
    }

    public function releaseDocument(Document $document): RedirectResponse
    {
        $document->update(['status' => 'released', 'released_at' => now()]);
        return back()->with('toast', ['type' => 'success', 'message' => 'Document released to resident.']);
    }

    // ------------------------------------------------------------------
    // Skills / Services / Businesses — Secretary stage of approval
    // ------------------------------------------------------------------

    public function skills(): View
    {
        $items = SkillService::with('user')
            ->where('status', 'pending')
            ->where('approved_by_secretary', false)
            ->latest()->paginate(15);
        return view('secretary.skills', compact('items'));
    }

    public function approveSkill(SkillService $skill): RedirectResponse
    {
        $skill->markApproved('secretary', Auth::id());
        return back()->with('toast', ['type' => 'success', 'message' => 'Forwarded to Kagawad.']);
    }

    public function businesses(): View
    {
        $items = Business::with('owner')
            ->where('status', 'pending')
            ->where('approved_by_secretary', false)
            ->latest()->paginate(15);
        return view('secretary.businesses', compact('items'));
    }

    public function approveBusiness(Business $business): RedirectResponse
    {
        $business->markApproved('secretary', Auth::id());
        return back()->with('toast', ['type' => 'success', 'message' => 'Forwarded to Kagawad.']);
    }
}
