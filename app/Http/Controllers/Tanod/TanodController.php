<?php

namespace App\Http\Controllers\Tanod;

use App\Http\Controllers\Controller;
use App\Models\BlotterRecord;
use App\Models\Complaint;
use App\Models\HearingSchedule;
use App\Models\MediationSchedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TanodController extends Controller
{
    public function dashboard(): View
    {
        return view('tanod.dashboard', [
            'open_blotter'       => BlotterRecord::whereIn('status', ['open', 'investigating'])->count(),
            'pending_complaints' => Complaint::where('status', 'pending_review')->count(),
            'today_mediation'    => MediationSchedule::whereDate('scheduled_at', today())->count(),
            'today_hearing'      => HearingSchedule::whereDate('scheduled_at', today())->count(),
            'recent_blotter'     => BlotterRecord::latest()->limit(10)->get(),
        ]);
    }

    public function complaints(Request $request): View
    {
        $status = $request->query('status');
        $items  = Complaint::with('complainant')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()->paginate(15);
        return view('tanod.complaints', compact('items', 'status'));
    }

    public function reviewComplaint(Complaint $complaint): View
    {
        $complaint->load('evidence', 'mediations', 'hearings');
        return view('tanod.complaint-show', compact('complaint'));
    }

    public function updateStatus(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending_review,under_investigation,scheduled_for_mediation,scheduled_for_hearing,resolved,dismissed,escalated'],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ]);

        $complaint->update([
            'status'              => $data['status'],
            'assigned_officer_id' => $complaint->assigned_officer_id ?? Auth::id(),
            'resolution_notes'    => $data['notes'] ?? $complaint->resolution_notes,
            'resolved_at'         => in_array($data['status'], ['resolved', 'dismissed']) ? now() : $complaint->resolved_at,
        ]);
        return back()->with('toast', ['type' => 'success', 'message' => 'Complaint status updated.']);
    }

    public function blotterIndex(): View
    {
        $items = BlotterRecord::with('recorder')->latest()->paginate(15);
        return view('tanod.blotter', compact('items'));
    }

    public function blotterStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'complaint_id'  => ['nullable', 'exists:complaints,id'],
            'incident_type' => ['required', 'string', 'max:120'],
            'narrative'     => ['required', 'string', 'min:20'],
            'location'      => ['required', 'string', 'max:255'],
            'incident_at'   => ['required', 'date'],
            'parties'       => ['nullable', 'array'],
        ]);

        $record = BlotterRecord::create([
            'recorded_by_id'   => Auth::id(),
            'complaint_id'     => $data['complaint_id'] ?? null,
            'incident_type'    => $data['incident_type'],
            'narrative'        => $data['narrative'],
            'location'         => $data['location'],
            'incident_at'      => $data['incident_at'],
            'parties_involved' => $data['parties'] ?? [],
            'status'           => 'open',
        ]);

        return back()->with('toast', ['type' => 'success', 'message' => "Blotter {$record->reference_no} recorded."]);
    }

    public function scheduleMediation(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
            'venue'        => ['required', 'string', 'max:120'],
        ]);

        MediationSchedule::create(array_merge($data, [
            'complaint_id'       => $complaint->id,
            'assigned_officer_id'=> Auth::id(),
            'status'             => 'scheduled',
        ]));

        $complaint->update(['status' => 'scheduled_for_mediation']);

        return back()->with('toast', ['type' => 'success', 'message' => 'Mediation scheduled.']);
    }

    public function scheduleHearing(Request $request, Complaint $complaint): RedirectResponse
    {
        $data = $request->validate([
            'scheduled_at' => ['required', 'date', 'after:now'],
            'venue'        => ['required', 'string', 'max:120'],
        ]);

        HearingSchedule::create(array_merge($data, [
            'complaint_id'       => $complaint->id,
            'assigned_officer_id'=> Auth::id(),
            'status'             => 'scheduled',
        ]));

        $complaint->update(['status' => 'scheduled_for_hearing']);

        return back()->with('toast', ['type' => 'success', 'message' => 'Hearing scheduled.']);
    }
}
