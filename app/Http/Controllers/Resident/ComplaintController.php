<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\ComplaintEvidence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    public function index(): View
    {
        $complaints = Complaint::where('complainant_id', Auth::id())
            ->latest()->paginate(10);

        return view('resident.complaints.index', compact('complaints'));
    }

    public function create(): View
    {
        return view('resident.complaints.create', [
            'categories' => config('panipone.complaint_categories'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $maxSize  = (int) config('panipone.uploads.max_size_kb', 5120);
        $mimes    = implode(',', config('panipone.uploads.allowed_mimes'));

        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'category'          => ['required', 'in:'.implode(',', config('panipone.complaint_categories'))],
            'custom_category'   => ['nullable', 'required_if:category,Others', 'string', 'max:100'],
            'respondent_name'   => ['required', 'string', 'max:255'],
            'incident_date'     => ['required', 'date', 'before_or_equal:today'],
            'incident_location' => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string', 'min:20'],
            'witness_name'      => ['nullable', 'string', 'max:255'],
            'witness_contact'   => ['nullable', 'string', 'max:20'],
            'evidence.*'        => ['nullable', "file", "mimes:{$mimes}", "max:{$maxSize}"],
        ]);

        $complaint = DB::transaction(function () use ($data, $request) {
            $complaint = Complaint::create([
                'complainant_id'  => Auth::id(),
                'title'           => $data['title'],
                'category'        => $data['category'],
                'custom_category' => $data['custom_category'] ?? null,
                'respondent_name' => $data['respondent_name'],
                'incident_date'   => $data['incident_date'],
                'incident_location'=> $data['incident_location'],
                'description'     => $data['description'],
                'witness_name'    => $data['witness_name'] ?? null,
                'witness_contact' => $data['witness_contact'] ?? null,
                'status'          => 'pending_review',
            ]);

            foreach ((array) $request->file('evidence', []) as $file) {
                if (! $file) continue;
                $path = $file->store("complaints/{$complaint->id}", 'uploads');
                ComplaintEvidence::create([
                    'complaint_id'   => $complaint->id,
                    'file_path'      => $path,
                    'original_name'  => $file->getClientOriginalName(),
                    'mime_type'      => $file->getMimeType(),
                    'size_bytes'     => $file->getSize(),
                    'uploaded_by_id' => Auth::id(),
                ]);
            }
            return $complaint;
        });

        return redirect()->route('resident.complaints.show', $complaint)->with('toast', [
            'type' => 'success',
            'message' => "Complaint {$complaint->reference_no} filed. Reference number sent to your email.",
        ]);
    }

    public function show(Complaint $complaint): View
    {
        abort_unless($complaint->complainant_id === Auth::id(), 403);
        $complaint->load('evidence', 'mediations.officer', 'hearings.officer', 'officer');
        return view('resident.complaints.show', compact('complaint'));
    }
}
