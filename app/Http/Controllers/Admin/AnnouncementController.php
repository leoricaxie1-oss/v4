<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(): View
    {
        $items = Announcement::with('author')->latest()->paginate(15);
        return view('admin.announcements.index', compact('items'));
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:160'],
            'body'     => ['required', 'string'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'is_published' => ['nullable', 'boolean'],
            'cover'    => ['nullable', 'image', 'max:5120'],
        ]);

        $data['slug']         = Str::slug($data['title']).'-'.Str::lower(Str::random(6));
        $data['author_id']    = Auth::id();
        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        $data['published_at'] = $data['is_published'] ? now() : null;

        if ($request->hasFile('cover')) {
            $data['cover_path'] = $request->file('cover')->store('announcements', 'public');
        }
        unset($data['cover']);

        Announcement::create($data);
        return redirect()->route('admin.announcements.index')->with('toast', ['type' => 'success', 'message' => 'Announcement created.']);
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $data = $request->validate([
            'title'    => ['required', 'string', 'max:160'],
            'body'     => ['required', 'string'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $data['is_published'] = (bool) ($data['is_published'] ?? false);
        if ($data['is_published'] && ! $announcement->published_at) {
            $data['published_at'] = now();
        }

        $announcement->update($data);
        return back()->with('toast', ['type' => 'success', 'message' => 'Announcement updated.']);
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();
        return back()->with('toast', ['type' => 'success', 'message' => 'Announcement removed.']);
    }
}
