<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\SkillService;
use App\Rules\PhilippineMobile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SkillServiceController extends Controller
{
    public function index(): View
    {
        $items = SkillService::where('user_id', Auth::id())->latest()->paginate(10);
        return view('resident.skills.index', compact('items'));
    }

    public function create(): View
    {
        return view('resident.skills.create', [
            'categories' => config('panipone.skill_categories'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category'        => ['required', 'in:'.implode(',', config('panipone.skill_categories'))],
            'custom_category' => ['nullable', 'required_if:category,Others', 'string', 'max:80'],
            'title'           => ['required', 'string', 'max:120'],
            'description'     => ['required', 'string', 'min:20'],
            'rate'            => ['nullable', 'numeric', 'min:0'],
            'rate_unit'       => ['nullable', 'string', 'max:30'],
            'contact_email'   => ['required', 'email'],
            'contact_phone'   => ['required', new PhilippineMobile],
            'photo'           => ['nullable', 'image', 'max:5120'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('skills', 'public');
        }
        unset($data['photo']);

        $data['user_id'] = Auth::id();
        $data['status']  = 'pending';

        $skill = SkillService::create($data);

        return redirect()->route('resident.skills.index')->with('toast', [
            'type' => 'success',
            'message' => "Skill/service \"{$skill->title}\" submitted for approval.",
        ]);
    }

    public function edit(SkillService $skill): View
    {
        abort_unless($skill->user_id === Auth::id(), 403);
        return view('resident.skills.edit', [
            'skill'      => $skill,
            'categories' => config('panipone.skill_categories'),
        ]);
    }

    public function update(Request $request, SkillService $skill): RedirectResponse
    {
        abort_unless($skill->user_id === Auth::id(), 403);

        $data = $request->validate([
            'title'       => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'min:20'],
            'rate'        => ['nullable', 'numeric', 'min:0'],
            'rate_unit'   => ['nullable', 'string', 'max:30'],
            'is_active'   => ['nullable', 'boolean'],
        ]);

        $skill->update($data);
        return back()->with('toast', ['type' => 'success', 'message' => 'Updated.']);
    }

    public function destroy(SkillService $skill): RedirectResponse
    {
        abort_unless($skill->user_id === Auth::id(), 403);
        $skill->delete();
        return back()->with('toast', ['type' => 'success', 'message' => 'Listing removed.']);
    }
}
