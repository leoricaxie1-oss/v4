<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        $documents = Document::where('user_id', Auth::id())
            ->latest()->paginate(10);

        return view('resident.documents.index', compact('documents'));
    }

    public function create(): View
    {
        return view('resident.documents.create', [
            'types' => config('panipone.documents'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $types = array_keys(config('panipone.documents'));

        $data = $request->validate([
            'document_type' => ['required', 'in:'.implode(',', $types)],
            'purpose'       => ['required', 'string', 'max:255'],
            'details'       => ['nullable', 'string'],
        ]);

        $cfg = config("panipone.documents.{$data['document_type']}");

        $doc = Document::create([
            'user_id'      => Auth::id(),
            'document_type'=> $data['document_type'],
            'purpose'      => $data['purpose'],
            'details'      => $data['details'] ?? null,
            'fee'          => $cfg['fee'] ?? 0,
            'status'       => 'pending_review',
        ]);

        return redirect()->route('resident.documents.show', $doc)
            ->with('toast', ['type' => 'success', 'message' => "Document request {$doc->reference_no} submitted."]);
    }

    public function show(Document $document): View
    {
        abort_unless($document->user_id === Auth::id(), 403);
        return view('resident.documents.show', compact('document'));
    }

    public function destroy(Document $document): RedirectResponse
    {
        abort_unless($document->user_id === Auth::id(), 403);
        abort_unless($document->status === 'pending_review', 422, 'Cannot cancel after processing.');
        $document->update(['status' => 'cancelled']);
        return back()->with('toast', ['type' => 'info', 'message' => 'Request cancelled.']);
    }
}
