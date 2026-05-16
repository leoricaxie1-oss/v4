<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $conversations = Auth::user()->conversations()
            ->with(['participants', 'messages' => fn ($q) => $q->latest()->limit(1)])
            ->latest('last_message_at')->paginate(15);

        return view('resident.messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        abort_unless($conversation->participants->contains(Auth::id()), 403);
        $messages = $conversation->messages()->with('sender')->oldest()->paginate(50);
        $conversation->participants()->updateExistingPivot(Auth::id(), ['last_read_at' => now()]);
        return view('resident.messages.show', compact('conversation', 'messages'));
    }

    public function send(Request $request, Conversation $conversation): RedirectResponse
    {
        abort_unless($conversation->participants->contains(Auth::id()), 403);

        $data = $request->validate([
            'body'       => ['required', 'string', 'max:2000'],
            'attachment' => ['nullable', 'file', 'max:5120'],
        ]);

        $attachment = $request->file('attachment')?->store('messages', 'uploads');

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id'       => Auth::id(),
            'body'            => $data['body'],
            'attachment_path' => $attachment,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back();
    }
}
