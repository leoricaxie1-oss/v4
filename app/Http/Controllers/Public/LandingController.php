<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Business;
use App\Models\SkillService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $skills = SkillService::public()
            ->when($search, fn ($q) => $q->where(function ($w) use ($search) {
                $w->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('custom_category', 'like', "%{$search}%");
            }))
            ->latest()->limit(8)->get();

        $businesses = Business::public()
            ->when($search, fn ($q) => $q->where('business_name', 'like', "%{$search}%"))
            ->latest()->limit(8)->get();

        $announcements = Announcement::published()->latest('published_at')->limit(5)->get();

        return view('landing.index', compact('skills', 'businesses', 'announcements', 'search'));
    }

    public function about(): View
    {
        return view('landing.about');
    }

    public function contact(): View
    {
        return view('landing.contact');
    }
}
